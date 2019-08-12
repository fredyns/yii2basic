<?php

namespace app\generators\crud\providers;

use schmunk42\giiant\generators\model\Generator as ModelGenerator;
use yii\db\ActiveRecord;
use yii\db\ColumnSchema;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class RelationProvider extends \schmunk42\giiant\base\Provider
{
    /**
     * @var null can be null (default) or `select2`
     */
    public $inputWidget = 'select2';

    /**
     * @var bool wheter to skip non-existing columns in relation grid
     *
     * @since 0.6
     */
    public $skipVirtualAttributes = false;

    /**
     * Formatter for relation form inputs.
     *
     * Renders a drop-down list for a `hasOne`/`belongsTo` relation
     *
     * @param $attribute
     *
     * @return null|string
     */
    public function activeField($attribute)
    {
        $column = $this->generator->getColumnByAttribute($attribute);
        if (!$column) {
            return;
        }

        // TODO: NoSQL hotfix
        if (is_string($column)) {
            return null;
        }
        $relation = $this->generator->getRelationByColumn($this->generator->modelClass, $column, ['belongs_to']);
        $giiConfigs = \app\generators\giiconfig\Generator::readMetadata();
        $select2 = ArrayHelper::getValue($giiConfigs, $this->generator->getTableSchema()->fullName.'.select2.'.$column->name);

        if ($select2) {
            $pk = key($relation->link);
            $name = $this->generator->getModelNameAttribute($relation->modelClass);
            $method = __METHOD__;
            return <<<EOS

// generated by {$method}
    \$form
    ->field(\$model, '{$column->name}')
    ->widget(\kartik\select2\Select2::class, [
        'initValueText' => \yii\helpers\ArrayHelper::getValue(\$model, '{$select2['relationName']}.{$name}', \$model->{$column->name}),
        'options' => ['placeholder' => Yii::t('app', 'searching...')],
        'pluginOptions' => [
            'allowClear' => true,
            'tags' => false,
            'minimumInputLength' => 2,
            'language' => [
                'errorLoading' => new \yii\web\JsExpression('function () { return "'.{$this->generator->generateString('waiting results...')}.'"; }'),
            ],
            'ajax' => [
                'url' => \yii\helpers\Url::to(['{$select2['uri']}']),
                'dataType' => 'json',
                'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new \yii\web\JsExpression('function (markup) { return markup; }'),
            'templateResult' => new \yii\web\JsExpression('function(item) { return item.text; }'),
            'templateSelection' => new \yii\web\JsExpression('function (item) { return item.text; }'),
        ],
    ])
;

EOS;
        }
    }

    /**
     * Formatter for detail view relation attributes.
     *
     * Renders a link to the related detail view
     *
     * @param $attribute ColumnSchema
     *
     * @return null|string
     */
    public function attributeFormat($attribute)
    {
        $column = $this->generator->getColumnByAttribute($attribute);
        if (!$column) {
            return;
        }

        // TODO: NoSQL hotfix
        if (is_string($column)) {
            return "'$column'";
        }

        // handle columns with a primary key, to create links in pivot tables (changed at 0.3-dev; 03.02.2015)
        // TODO double check with primary keys not named `id` of non-pivot tables
        // TODO Note: condition does not apply in every case
        if ($column->isPrimaryKey) {
            //return null; #TODO: double check with primary keys not named `id` of non-pivot tables
        }

        $relation = $this->generator->getRelationByColumn($this->generator->modelClass, $column, ['belongs_to']);
        if ($relation) {
            if ($relation->multiple) {
                return;
            }
            $title = $this->generator->getModelNameAttribute($relation->modelClass);
            $route = $this->generator->createRelationRoute($relation, 'view');

            // prepare URLs
            $routeAttach = 'create';
            $routeIndex = $this->generator->createRelationRoute($relation, 'index');

            $modelClass = $this->generator->modelClass;
            $relationProperty = lcfirst((new ModelGenerator())->generateRelationName(
                    [$relation], $modelClass::getTableSchema(), $column->name, $relation->multiple
            ));
            $relationModel = new $relation->modelClass();
            $relationModelName = StringHelper::basename($modelClass);
            $pks = $relationModel->primaryKey();
            $paramArrayItems = '';
            foreach ($pks as $attr) {
                $paramArrayItems .= "'{$attr}' => \$model->{$relationProperty}->{$attr},";
            }
            $attachArrayItems = "'{$relationModelName}'=>['{$column->name}' => \$model->{$column->name}]";

            $method = __METHOD__;
            $code = <<<EOS
        // generated by {$method}
            [
                'attribute' => '{$column->name}',
                'format' => 'html',
                'value' => ArrayHelper::getValue(\$model, '{$relationProperty}.{$title}', '<span class="label label-warning">?</span>'),
            ]
EOS;

            return $code;
        }
    }

    /**
     * Formatter for relation grid columns.
     *
     * Renders a link to the related detail view
     *
     * @param $attribute ColumnSchema
     * @param $model ActiveRecord
     *
     * @return null|string
     */
    public function columnFormat($attribute, $model)
    {
        $column = $this->generator->getColumnByAttribute($attribute, $model);
        if (!$column) {
            return;
        }

        // TODO: NoSQL hotfix
        if (is_string($column)) {
            return $column;
        }

        // handle columns with a primary key, to create links in pivot tables (changed at 0.3-dev; 03.02.2015)
        // TODO double check with primary keys not named `id` of non-pivot tables
        // TODO Note: condition does not apply in every case
        if ($column->isPrimaryKey) {
            //return null;
        }

        $relation = $this->generator->getRelationByColumn($model, $column, ['belongs_to']);
        if ($relation) {
            if ($relation->multiple) {
                return;
            }
            $title = $this->generator->getModelNameAttribute($relation->modelClass);
            $method = __METHOD__;
            $modelClass = $this->generator->modelClass;
            $relationProperty = lcfirst((new ModelGenerator())->generateRelationName(
                    [$relation], $modelClass::getTableSchema(), $column->name, $relation->multiple
            ));
            $relationModel = new $relation->modelClass();
            $pks = $relationModel->primaryKey();
            $paramArrayItems = '';

            foreach ($pks as $attr) {
                $paramArrayItems .= "'{$attr}' => \$rel->{$attr},";
            }

            $relModelClassname = get_class($model);
            $code = <<<EOS
// generated by {$method}
[
    'attribute' => '{$column->name}',
    'format' => 'html',
    'value' => function (\$model) {
        /* @var \$model \\{$relModelClassname} */
        return ArrayHelper::getValue(\$model, '{$relationProperty}.{$title}');
    },
]
EOS;

            return $code;
        }
    }

    /**
     * Renders a grid view for a given relation.
     *
     * @param $name
     * @param $relation
     * @param bool $showAllRecords
     *
     * @return mixed|string
     */
}