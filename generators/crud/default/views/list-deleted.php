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
$nameAttribute = $generator->getNameAttribute();
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
use cornernote\returnurl\ReturnUrl;
use <?= $acNameSpace ?>\<?= $acClassName ?>;
use <?= $generator->searchModelClass ?>;
use <?= $generator->modelClass ?>;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel <?= $searchClassName ?> */

$this->title = Yii::t('<?= $messageCategory ?>','Deleted <?= $modelName ?>');
<?php if ($moduleId != 'app'): ?>
$this->params['breadcrumbs'][] = Yii::t('<?= $moduleId.'/texts' ?>', '<?= Inflector::camel2words($moduleId) ?>');
<?php endif; ?>
<?php if ($subPath): ?>
$this->params['breadcrumbs'][] = Yii::t('<?= $messageCategory ?>', '<?= Inflector::camel2words($subPath) ?>');
<?php endif; ?>
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = <?=$generator->generateString('Deleted')?>;
?>

<div class="app-crud <?= $modelSlug ?>-deleted">

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
                ButtonDropdown::widget([
                    'label' => 'Menu',
                    'options' => [
                        'class' => 'btn btn-primary',
                    ],
                    'dropdown' => [
                        'items' => [
                            [
                                'label' => '<span class="glyphicon glyphicon-list"></span> '.Yii::t('cruds', "Index List"),
                                'encode' => FALSE,
                                'url' => ['index'],
                                'visible' => <?= $acClassName ?>::canIndex(),
                            ],
                            [
                                'label' => '<span class="glyphicon glyphicon-hdd"></span> '.Yii::t('cruds', "List Archive"),
                                'encode' => FALSE,
                                'url' => ['list-archive'],
                                'visible' => <?= $acClassName ?>::canListArchive(),
                            ],
                        ],
                    ],
                ])
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
                    'class' => \kartik\grid\ActionColumn::class,
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            $label = '<span class="glyphicon glyphicon-eye-open"></span>';
                            $options = [
                                'title' => Yii::t('cruds', 'View'),
                                'aria-label' => Yii::t('cruds', 'View'),
                                'data-pjax' => '0',
                            ];
                            return Html::a($label, $url, $options);
                        },
                    ],
                    'urlCreator' => function($action, $model, $key, $index) {
                        // using the column name as key, not mapping to 'id' like the standard generator
                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                        $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id.'/'.$action : $action;
                        $params['ru'] = ReturnUrl::getToken();
                        return Url::toRoute($params);
                    },
                    'contentOptions' => ['nowrap' => 'nowrap']
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
