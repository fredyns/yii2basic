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
$nameAttribute = $generator->getNameAttribute();
$safeAttributes = $model->safeAttributes();

if (empty($safeAttributes)) {
    $safeAttributes = $tableSchema->columnNames;
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Url;
use <?= $menuNameSpace."\\".$menuClassName ?>;
use <?= $generator->modelClass ?>;
use app\widgets\SplitDropdown;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel <?= $generator->searchModelClass ?> */

$this->title = Yii::t('<?= $subPath ? $subPath : 'pages' ?>','Deleted <?= Inflector::pluralize($modelName) ?>');
<?php if ($subPath): ?>
$this->params['breadcrumbs'][] = Yii::t('<?= $subPath ?>', '<?= Inflector::camel2words($subPath) ?>');
<?php endif; ?>
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = <?=$generator->generateString('Deleted')?>;
?>

<div class="giiant-crud <?= $modelSlug ?>-deleted">

    <div class="clearfix crud-navigation" style="padding-top: 30px;">
        <div class="pull-left">
            <h1 style="margin-top: 0;">
                <?= '<?=' ?> $searchModel->modelLabel(TRUE) ?>
                <small>
                    <?= '<?= '.$generator->generateString('Deleted') ?> ?>
                </small>
            </h1>
        </div>
        <div class="pull-right">
            <div>
                <?= "<?=\n" ?>
                SplitDropdown::widget([
                    'label' => <?= $menuClassName ?>::iconFor('create').'&nbsp; '.<?= $menuClassName ?>::labelFor('create'),
                    'encodeLabel' => FALSE,
                    'buttonAction' => 'create',
                    'options' => [
                        'class' => 'btn btn-primary',
                    ],
                    'dropdownActions' => [
                        'index',
                        'archive',
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

    <?= '<?php' ?> //= $this->render('_search', ['model' => $searchModel]); ?>

    <hr style="margin-top: 0;" />

    <?= "<?php\n" ?>
    \yii\widgets\Pjax::begin([
        'id' => 'pjax-main',
        'enableReplaceState' => false,
        'linkSelector' => '#pjax-main ul.pagination a, th a',
        'clientOptions' => [
            'pjax:success' => 'function(){alert("yo")}',
        ],
    ]);
    ?>

<?php if ($generator->indexWidgetType === 'grid'): ?>
    <div>
        <?= "<?=\n" ?>
        \kartik\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => [
                'class' => yii\widgets\LinkPager::class,
                'firstPageLabel' => <?= $generator->generateString('First') ?>,
                'lastPageLabel' => <?= $generator->generateString('Last').",\n" ?>
            ],
<?php if ($generator->searchModelClass !== ''): ?>
            'filterModel' => $searchModel,
<?php endif; ?>
            'responsive' => false,
            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
            'headerRowOptions' => ['class' => 'x'],
            'columns' => [
                [
                    'class' => \kartik\grid\SerialColumn::class,
                ],
<?php $count = 0; ?>
<?php foreach ($safeAttributes as $attribute): ?>
<?php if ($rangeKey = array_search($attribute, $dateRange)):?>
                [
                    'attribute' => '<?= $attribute ?>',
                    'format' => [
                        'date',
                        'format' => 'eee, d MMM Y',
                    ],
                    'filter' => $searchModel-><?= $rangeKey ?>Search->filterWidget(),
                ],
<?php ++$count; ?>
<?php continue; ?>
<?php elseif ($rangeKey = array_search($attribute, $timestampRange)):?>
                [
                    'attribute' => '<?= $attribute ?>',
                    'format' => [
                        'datetime',
                        'format' => 'eee, d MMM Y, H:m',
                    ],
                    'filter' => $searchModel-><?= $rangeKey ?>Search->filterWidget(),
                ],
<?php ++$count; ?>
<?php continue; ?>
<?php elseif (substr_compare($attribute, '_date', -5, 5, true) === 0):?>
                [
                    'attribute' => '<?= $attribute ?>',
                    'format' => [
                        'datetime',
                        'format' => 'eee, d MMM Y, H:m',
                    ],
                ],
<?php ++$count; ?>
<?php continue; ?>
<?php elseif (substr_compare($attribute, '_at', -3, 3, true) === 0):?>
                [
                    'attribute' => '<?= $attribute ?>',
                    'format' => [
                        'datetime',
                        'format' => 'eee, d MMM Y, H:m',
                    ],
                ],
<?php ++$count; ?>
<?php continue; ?>
<?php endif;?>
<?php
$format = trim($generator->columnFormat($attribute, $model));
    if ($format == false) {
        continue;
    }
?>
<?php if (++$count < $generator->gridMaxColumns):?>
                <?= str_replace("\n", "\n".str_repeat(' ', 16), $format) . ",\n" ?>
<?php else:?>
                <?= str_replace("\n", "\n".str_repeat(' ', 16).'//  ', $format) . ",\n" ?>
<?php endif;?>
<?php endforeach;?>
                [
                    'class' => \app\components\ActionColumn::class,
                    'contentRenderer' => function($model, $key, $index) {
                        return SplitDropdown::widget([
                                'model' => $model,
                                'label' => <?= $menuClassName ?>::iconFor('view').' '.<?= $menuClassName ?>::labelFor('view'),
                                'buttonAction' => 'view',
                                'dropdownActions' => [
                                    'view',
                                    [
                                        'restore',
                                    ],
                                ],
                                'dropdownButtons' => <?= $menuClassName ?>::dropdownButtons(),
                                'urlCreator' => function($action, $model) {
                                    return <?= $menuClassName ?>::createUrlFor($action, $model);
                                },
                        ]);
                    },
                ],
            ],
        ]);
        ?>
    </div>

<?php else: ?>
    <?= "<?= \n" ?>
    ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]);
    ?>

<?php endif; ?>
    <?= "<?php \yii\widgets\Pjax::end() ?>\n"; ?>

</div>
