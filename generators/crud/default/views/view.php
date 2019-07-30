<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this \yii\web\View  */
/* @var $generator \app\generators\crud\Generator  */
/* @var $model \yii\db\ActiveRecord  */

## TODO: move to generator (?); cleanup
$model = new $generator->modelClass();
$model->setScenario('crud');
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $model->setScenario('default');
    $safeAttributes = $model->safeAttributes();
}
if (empty($safeAttributes)) {
    $safeAttributes = $model->getTableSchema()->columnNames;
}

$modelName = Inflector::camel2words(StringHelper::basename($model::className()));
$className = $model::className();
$urlParams = $generator->generateUrlParams();
$tableSchema = $generator->getTableSchema();
$haveID=($tableSchema->getColumn('id') !== null);
$softdelete = ($tableSchema->getColumn('is_deleted') !== null) && ($tableSchema->getColumn('deleted_at') !== null) && ($tableSchema->getColumn('deleted_by') !== null);
$subNameSpace = StringHelper::basename(StringHelper::dirname($model::className()));
$subPath = ($subNameSpace === 'models') ? FALSE : Inflector::camel2id($subNameSpace);

echo "<?php\n";
?>

use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/* @var $this yii\web\View  */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?>  */

$this->title = $model->modelLabel();
<?php if ($subPath): ?>
$this->params['breadcrumbs'][] = Yii::t('app', '<?= $subPath ?>');
<?php endif; ?>
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = <?= $generator->generateString('View') ?>;
?>
<div class="giiant-crud <?= Inflector::camel2id(StringHelper::basename($generator->modelClass), '-', true) ?>-view">

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
            <?="<?=\n"?>
            ButtonDropdown::widget([
                'label' => <?= $generator->generateString('Edit') ?>,
                'tagName' => 'a',
                'split' => true,
                'options' => [
                    'href' => ['update', <?= $urlParams ?>],
                    'class' => 'btn btn-info',
                ],
                'dropdown' => [
                    'encodeLabels' => FALSE,
                    'options' => [
                        'class' => 'dropdown-menu-right',
                    ],
                    'items' => [
                        '<li role="presentation" class="divider"></li>',
                        [
                            'label' => '<span class="glyphicon glyphicon-list"></span> '.<?= $generator->generateString('Full list') ?>,
                            'url' => ['index'],
                        ],
                        [
                            'label' => '<span class="glyphicon glyphicon-plus"></span> '.<?= $generator->generateString('New') ?>,
                            'url' => ['create'],
                        ],
                        '<li role="presentation" class="divider"></li>',
                        [
                            'label' => '<span class="glyphicon glyphicon-trash"></span> '.<?= $generator->generateString('Delete') ?>,
                            'url' => ['delete', <?= $urlParams ?>],
                            'linkOptions' => [
                                'data-confirm' => <?= $generator->generateString('Are you sure to delete this item?') ?>,
                                'data-method' => 'post',
                                'data-pjax' => FALSE,
                                'class' => 'label label-danger',
                            ],
<?php if($softdelete):?>
                            'visible' => ($model->is_deleted == FALSE),
                        ],
                        [
                            'label' => '<span class="glyphicon glyphicon-floppy-open"></span> '.<?= $generator->generateString('Restore') ?>,
                            'url' => ['delete', <?= $urlParams ?>],
                            'linkOptions' => [
                                'data-confirm' => <?= $generator->generateString('Are you sure to restore this item?') ?>,
                                'data-method' => 'post',
                                'data-pjax' => FALSE,
                                'class' => 'label label-info',
                            ],
                            'visible' => ($model->is_deleted),
<?php endif;?>
                        ],
                    ],
                ],
            ]);
            ?>
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
        <?= '<?=' ?> Yii::t('timestamp', 'Created') ?>
        <?= '<?=' ?> Yii::$app->formatter->asDate($model->created_at, "d MMMM Y '".Yii::t('timestamp', 'at')."' HH:mm") ?>
<?php if ($tableSchema->getColumn('created_by') !== null): ?>
        <?= '<?=' ?> Yii::t('timestamp', 'by') ?>
        <?= '<?=' ?> ArrayHelper::getValue($model, 'createdBy.username', '-') ?>
<?php endif; ?>
<?php if ($tableSchema->getColumn('updated_at') !== null): ?>
        <br/>
        <?= '<?=' ?> Yii::t('timestamp', 'Updated') ?>
        <?= '<?=' ?> Yii::$app->formatter->asDate($model->updated_at, "d MMMM Y '".Yii::t('timestamp', 'at')."' HH:mm") ?>
<?php if ($tableSchema->getColumn('updated_by') !== null): ?>
        <?= '<?=' ?> Yii::t('timestamp', 'by') ?>
        <?= '<?=' ?> ArrayHelper::getValue($model, 'updatedBy.username', '-') ?>
<?php endif; ?>
<?php endif; ?>
<?php if ($tableSchema->getColumn('deleted_at') !== null): ?>
        <?='<?php'?> if ($model->is_deleted): ?>
            <br/>
            <?= '<?=' ?> Yii::t('timestamp', 'Deleted') ?>
            <?= '<?=' ?> Yii::$app->formatter->asDate($model->deleted_at, "d MMMM Y '".Yii::t('timestamp', 'at')."' HH:mm") ?>
<?php if ($tableSchema->getColumn('deleted_by') !== null): ?>
            <?= '<?=' ?> Yii::t('timestamp', 'by') ?>
            <?= '<?=' ?> ArrayHelper::getValue($model, 'deletedBy.username', '-') ?>
<?php endif; ?>
        <?='<?php'?> endif; ?>
<?php endif; ?>
    </div>
<?php endif; ?>

</div>
