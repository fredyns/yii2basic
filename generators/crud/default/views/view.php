<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this \yii\web\View  */
/* @var $generator \app\generators\crud\Generator  */
/* @var $tableSchema \yii\db\TableSchema  */
/* @var $softdelete bool  */
/* @var $modelClassName string  */
/* @var $modelSlug string  */
/* @var $modelName string  */
/* @var $model \yii\db\ActiveRecord  */
/* @var $searchClassName string search model class name w/o namespace  */
/* @var $acNameSpace string action control namespace */
/* @var $acClassName string action control class name w/o namespace */
/* @var $controllerClassName string  */
/* @var $controllerNameSpace string  */
/* @var $moduleNameSpace string  */
/* @var $moduleId string  */
/* @var $subPath string  */
/* @var $messageCategory string  */
/* @var $apiNameSpace string  */
/* @var $dateRange string[]  */
/* @var $timestampRange string[]  */

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
use cornernote\returnurl\ReturnUrl;
use <?= $acNameSpace ?>\<?= $acClassName ?>;
use <?= $generator->modelClass ?>;

/* @var $this yii\web\View  */
/* @var $model <?= $modelClassName ?>  */

<?php if ($haveID): ?>
$this->title = Yii::t('<?= $messageCategory ?>','View <?= $modelName ?>').' #'.$model->id;
<?php else: ?>
$this->title = Yii::t('<?= $messageCategory ?>','View <?= $modelName ?>').' - '.$model-><?= $generator->getModelNameAttribute() ?>;
<?php endif; ?>
<?php if ($moduleId != 'app'): ?>
$this->params['breadcrumbs'][] = Yii::t('<?= $moduleId.'/texts' ?>', '<?= Inflector::camel2words($moduleId) ?>');
<?php endif; ?>
<?php if ($subPath): ?>
$this->params['breadcrumbs'][] = Yii::t('<?= $messageCategory ?>', '<?= Inflector::camel2words($subPath) ?>');
<?php endif; ?>
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = <?= $generator->generateString('View') ?>;
?>
<div class="app-crud <?= $modelSlug ?>-view">

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
                        <span class="badge"><?= $generator->generateString('deleted') ?></span>
                    <?= '<?php' ?> endif; ?>
<?php endif;?>
                </small>
            </h1>
        </div>

        <!-- menu buttons -->
        <div class="pull-right">
            <div>
                <?= "<?=\n" ?>
                ButtonDropdown::widget([
                    'label' => 'Menu',
                    'options' => [
                        'class' => 'btn btn-primary',
                    ],
                    'dropdown' => [
                        'items' => [
                            [
                                'label' => '<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('cruds', "Edit"),
                                'encode' => FALSE,
                                'url' => [
                                    'update',
                                    'id' => $model->id,
                                    'ru' => ReturnUrl::getToken(),
                                ],
                                'visible' => <?= $acClassName ?>::canUpdate(),
                            ],
                            //'<li role="presentation" class="divider"></li>',
                        ],
                    ],
                ])
                ?>
            </div>
        </div>

    </div>

    <hr/>

    <?= $generator->partialView('detail_prepend', $model); ?>

    <?= "<?=\n" ?>
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
<?php $hasManyRelations = $generator->getModelRelations($generator->modelClass, ['has_many']); ?>
<?php if (count($hasManyRelations) > 0): ?>
<?php foreach ($hasManyRelations as $name => $relation): ?>
<?php
if (method_exists($model, 'get'.$name) == FALSE) {
    continue;
}
?>
    <br/>
    <h3><?= '<?= ' ?>Yii::t('<?= $messageCategory ?>', '<?= Inflector::camel2words($name, TRUE) ?>') ?></h3>
    <div class="table-responsive">
        <?= "<?=\n" ?>
        \kartik\grid\GridView::widget([
            'layout' => '{summary}{pager}<br/>{items}{pager}',
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => $model->get<?= $name ?>(),
                'pagination' => [
                    'pageSize' => 20,
                    'pageParam' => 'page-<?= Inflector::slug($name) ?>',
                ],
            ]),
            'columns' => [
                [
                    'class' => \kartik\grid\SerialColumn::class,
                ],
<?php
$rel_model = new $relation->modelClass();
$allAttributes = $rel_model->safeAttributes();
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

    <div class="clearfix">
        <!-- danger menu buttons -->
        <div class='pull-right'>
            <div>
                <?= "<?php\n" ?>
                if (<?= $acClassName ?>::canDelete()) {
                    $label = '<span class="glyphicon glyphicon-trash"></span> '.Yii::t('cruds', 'Delete');
                    $options = [
                        'class' => 'btn btn-danger',
                        'title' => Yii::t('cruds', 'Delete'),
                        'aria-label' => Yii::t('cruds', 'Delete'),
                        'data-confirm' => Yii::t('cruds', 'Are you sure to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => FALSE,
                    ];
                    $url = [
                        'delete',
                        'id' => $model->id,
                        'ru' => ReturnUrl::urlToToken(Url::to(['index'])),
                    ];
                    echo Html::a($label, $url, $options);
                }
                ?>
            </div>
<?php if ($softdelete): ?>
            <div>
                <?= "<?php\n" ?>
                if (<?= $acClassName ?>::canRestore()) {
                    $label = '<span class="glyphicon glyphicon-refresh"></span> '.Yii::t('cruds', 'Restore');
                    $options = [
                        'class' => 'btn btn-warning',
                        'title' => Yii::t('cruds', 'Restore'),
                        'aria-label' => Yii::t('cruds', 'Restore'),
                        'data-confirm' => Yii::t('cruds', 'Are you sure to restore this item?'),
                        'data-method' => 'post',
                        'data-pjax' => FALSE,
                    ];
                    $url = [
                        'restore',
                        'id' => $model->id,
                        'ru' => ReturnUrl::urlToToken(Url::to(['view', 'id' => $model->id])),
                    ];
                    echo Html::a($label, $url, $options);
                }
                ?>
            </div>
<?php endif; ?>
        </div>
    </div>

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
