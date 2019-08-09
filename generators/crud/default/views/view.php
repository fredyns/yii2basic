<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this \yii\web\View  */
/* @var $generator \app\generators\crud\Generator  */
/* @var $tableSchema \yii\db\TableSchema  */
/* @var $giiConfigs array  */
/* @var $softdelete bool  */
/* @var $modelClassName string  */
/* @var $modelSlug string  */
/* @var $modelName string  */
/* @var $model \yii\db\ActiveRecord  */
/* @var $controllerClassName string  */
/* @var $controllerNameSpace string  */
/* @var $moduleNameSpace string  */
/* @var $subPath string  */
/* @var $actionParentNameSpace string  */
/* @var $actionParent string[]  */
/* @var $apiNameSpace string  */
/* @var $menuNameSpace string  */
/* @var $dateRange string[]  */
/* @var $timestampRange string[]  */

$menuClassName = $modelClassName.'Menu';
$urlParams = $generator->generateUrlParams();
$haveID = ($tableSchema->getColumn('id') !== null);
$safeAttributes = $model->safeAttributes();

if (empty($safeAttributes)) {
    $safeAttributes = $tableSchema->columnNames;
}

echo "<?php\n";
?>

use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use <?= $menuNameSpace."\\".$menuClassName ?>;
use app\components\Tabs;
use <?= $generator->modelClass ?>;
use app\widgets\SplitDropdown;

/* @var $this yii\web\View  */
/* @var $model <?= $modelClassName ?>  */

<?php if ($haveID): ?>
$this->title = Yii::t('<?= $subPath ? $subPath : 'pages' ?>','View <?= $modelName ?>').' #'.$model->id;
<?php else: ?>
$this->title = Yii::t('<?= $subPath ? $subPath : 'pages' ?>','View <?= $modelName ?>').' - '.$model-><?= $generator->getModelNameAttribute() ?>;
<?php endif; ?>
<?php if ($subPath): ?>
$this->params['breadcrumbs'][] = Yii::t('app', '<?= Inflector::camel2words($subPath) ?>');
<?php endif; ?>
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = <?= $generator->generateString('View') ?>;
?>
<div class="giiant-crud <?= $modelSlug ?>-view">

    <div class="clearfix crud-navigation" style="padding-top: 30px;">
        <div class="pull-left">
            <h1 style="margin-top: 0;">
                <?= '<?=' ?> $model->modelLabel() ?>
                <small>
<?php if($haveID):?>
                    #<?= '<?='?> $model->id ?>
<?php else:?>
                    <?= '<?='?> Html::encode($model-><?= $generator->getNameAttribute() ?>) ?>
<?php endif;?>
<?php if($softdelete):?>
                    <?= '<?php' ?> if ($model->is_deleted): ?>
                        <span class="badge">deleted</span>
                    <?= '<?php' ?> endif; ?>
<?php endif;?>
                </small>
            </h1>
        </div>

        <!-- menu buttons -->
        <div class='pull-right'>
            <div>
                <?= "<?=\n" ?>
                SplitDropdown::widget([
                    'model' => $model,
                    'label' => <?= $menuClassName ?>::iconFor('create').'&nbsp; '.<?= $menuClassName ?>::labelFor('create'),
                    'encodeLabel' => FALSE,
                    'buttonAction' => 'update',
                    'dropdownActions' => [
                        'view',
                        [
                            'delete',
<?php if($softdelete):?>
                            'restore',
<?php endif;?>
                        ],
                    ],
                    'dropdownButtons' => <?= $menuClassName ?>::dropdownButtons(),
                    'urlCreator' => function($action, $model) {
                        return <?= $menuClassName ?>::createUrlFor($action, $model);
                    },
                ]);
                ?>
            </div>
        </div>

    </div>

    <hr/>

    <?= $generator->partialView('detail_prepend', $model); ?>

    <?= "<?= \n" ?>
    DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
    foreach ($safeAttributes as $attribute) {
        $format = $generator->attributeFormat($attribute);
        if (!$format) {
            continue;
        } else {
            echo str_repeat(' ', 4).$format.",\n";
        }
    }
?>
        ],
    ]);
    ?>

    <?= $generator->partialView('detail_append', $model); ?>

    <hr/>
<?php $modelMeta = \app\generators\giiconfig\Generator::readMetadata(); ?>
<?php if (isset($modelMeta[$model::tableName()]['hasMany'])): ?>
<?php $subinfo_list = $modelMeta[$model::tableName()]['hasMany']; ?>
<?php $i18n_category = yii\helpers\ArrayHelper::getValue($modelMeta, $model::tableName().'.messageCategory', 'models'); ?>
<?php foreach ($subinfo_list as $rel_key => $rel_info): ?>
    <br/>
    <h3><?= '<?= ' ?>Yii::t('<?= $i18n_category ?>', '<?= Inflector::camel2words($rel_key, TRUE) ?>') ?></h3>
    <div class="table-responsive">
        <?= "<?=\n" ?>
        \kartik\grid\GridView::widget([
            'layout' => '{summary}{pager}<br/>{items}{pager}',
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => $model->get<?=$rel_key?>(),
                'pagination' => [
                    'pageSize' => 20,
                    'pageParam' => 'page-requestitems',
                ],
            ]),
            'columns' => [
                [
                    'class' => 'kartik\grid\SerialColumn',
                ],
<?php
$rel_modelclass = $rel_info['nameSpace'].'\\'.$rel_info['className'];
$rel_model = new $rel_modelclass;
$allAttributes = $model->safeAttributes();
$skipCols = ['id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_deleted', 'deleted_at', 'deleted_by'];
$safeAttributes = array_diff($allAttributes, $skipCols);

$max_columns = 12;
$count = 0;
foreach ($safeAttributes as $attribute) {
    $format = trim($generator->columnFormat($attribute, $rel_model));
    if ($format == false) {
        $format = "'$attribute'";
    }
    if (++$count < $max_columns) {
        echo str_repeat(' ', 16).str_replace("\n", "\n".str_repeat(' ', 16), $format) . ",\n";
    } else {
        echo str_repeat(' ', 16)."/* //\n"
            .str_repeat(' ', 16).str_replace("\n", "\n".str_repeat(' ', 16), $format).",\n"
            .str_repeat(' ', 16)."// */\n";
    }
}
?>
            ],
        ]);
        ?>
    </div>
<?php endforeach; ?>
<?php endif; ?>
    <br/>
    <hr/>
<?php if ($tableSchema->getColumn('created_at') !== null): ?>

    <div style="font-size: 75%; font-style: italic;">
        <?= '<?=' ?> Yii::t('record-info', 'Created') ?>
        <?= '<?=' ?> Yii::$app->formatter->asDate($model->created_at, "eeee, d MMMM Y '".Yii::t('record-info', 'at')."' HH:mm") ?>
<?php if ($tableSchema->getColumn('created_by') !== null): ?>
        <?= '<?=' ?> Yii::t('record-info', 'by') ?>
        <?= '<?=' ?> ArrayHelper::getValue($model, 'createdBy.username', Yii::t('app', 'Guest')) ?>
<?php endif; ?>
<?php if ($tableSchema->getColumn('updated_at') !== null): ?>
        <br/>
        <?= '<?=' ?> Yii::t('record-info', 'Updated') ?>
        <?= '<?=' ?> Yii::$app->formatter->asDate($model->updated_at, "eeee, d MMMM Y '".Yii::t('record-info', 'at')."' HH:mm") ?>
<?php if ($tableSchema->getColumn('updated_by') !== null): ?>
        <?= '<?=' ?> Yii::t('record-info', 'by') ?>
        <?= '<?=' ?> ArrayHelper::getValue($model, 'updatedBy.username', Yii::t('app', 'Guest')) ?>
<?php endif; ?>
<?php endif; ?>
<?php if ($tableSchema->getColumn('deleted_at') !== null): ?>
        <?='<?php'?> if ($model->is_deleted): ?>
            <br/>
            <?= '<?=' ?> Yii::t('record-info', 'Deleted') ?>
            <?= '<?=' ?> Yii::$app->formatter->asDate($model->deleted_at, "eeee, d MMMM Y '".Yii::t('record-info', 'at')."' HH:mm") ?>
<?php if ($tableSchema->getColumn('deleted_by') !== null): ?>
            <?= '<?=' ?> Yii::t('record-info', 'by') ?>
            <?= '<?=' ?> ArrayHelper::getValue($model, 'deletedBy.username', Yii::t('app', 'Guest')) ?>
<?php endif; ?>
        <?='<?php'?> endif; ?>
<?php endif; ?>
    </div>
<?php endif; ?>

</div>
