<?php

use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use cornernote\returnurl\ReturnUrl;
use app\lib\sample\person\PersonAC;
use app\lib\sample\person\PersonSearch;
use app\models\sample\Person;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel PersonSearch */

$this->title = $searchModel->modelLabel(true);
$this->params['breadcrumbs'][] = Yii::t('app/sample/texts', 'Sample');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="app-crud person-index">

    <div class="clearfix crud-navigation" style="padding-top: 30px;">
        <div class="pull-left">
            <h1 style="margin-top: 0;">
                <?= $searchModel->modelLabel(TRUE) ?>
                <small>
                    <?= Yii::t('cruds', 'List') ?>
                </small>
            </h1>
        </div>
        <div class="pull-right">
            <div>
                <?=
                ButtonDropdown::widget([
                    'label' => 'Menu',
                    'options' => [
                        'class' => 'btn btn-primary',
                    ],
                    'dropdown' => [
                        'items' => [
                            [
                                'label' => '<span class="glyphicon glyphicon-plus"></span> '.Yii::t('cruds', "New"),
                                'encode' => FALSE,
                                'url' => [
                                    'create',
                                    'ru' => ReturnUrl::getToken(),
                                ],
                                'visible' => PersonAC::canCreate(),
                            ],
                            //'<li role="presentation" class="divider"></li>',
                            [
                                'label' => '<span class="glyphicon glyphicon-trash"></span> '.Yii::t('cruds', "List Deleted"),
                                'encode' => FALSE,
                                'url' => ['list-deleted'],
                                'visible' => PersonAC::canListDeleted(),
                            ],
                            [
                                'label' => '<span class="glyphicon glyphicon-hdd"></span> '.Yii::t('cruds', "List Archive"),
                                'encode' => FALSE,
                                'url' => ['list-archive'],
                                'visible' => PersonAC::canListArchive(),
                            ],
                        ],
                    ],
                ])
                ?>
            </div>
        </div>
    </div>

    <?php //= $this->render('_search', ['model' => $searchModel]); ?>

    <hr style="margin-top: 0;" />

    <?php
    \yii\widgets\Pjax::begin([
        'id' => 'pjax-main',
        'enableReplaceState' => false,
        'linkSelector' => '#pjax-main ul.pagination a, th a',
        'clientOptions' => [
            'pjax:success' => 'function(){alert("yo")}',
        ],
    ]);
    ?>

    <div>
        <?=
        \kartik\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => [
                'class' => \yii\widgets\LinkPager::class,
                'firstPageLabel' => Yii::t('cruds', 'First'),
                'lastPageLabel' => Yii::t('cruds', 'Last'),
            ],
            'filterModel' => $searchModel,
            'responsive' => false,
            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
            'headerRowOptions' => ['class' => 'x'],
            'columns' => [
                [
                    'class' => \kartik\grid\SerialColumn::class,
                ],
                'name',
                [
                    'class' => \kartik\grid\ActionColumn::class,
                    'template' => '{view} {update}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            $label = '<span class="glyphicon glyphicon-eye-open"></span>';
                            $hover_text = Yii::t('cruds', 'view this record');
                            $options = [
                                'title' => $hover_text,
                                'aria-label' => $hover_text,
                                'data-pjax' => '0',
                            ];
                            return Html::a($label, $url, $options);
                        },
                        'update' => function ($url, $model, $key) {
                            $label = '<span class="glyphicon glyphicon-pencil"></span>';
                            $hover_text = Yii::t('cruds', 'update this record');
                            $options = [
                                'title' => $hover_text,
                                'aria-label' => $hover_text,
                                'data-pjax' => '0',
                            ];
                            return Html::a($label, $url, $options);
                        },
                        'delete' => function ($url, $model, $key) {
                            $label = '<span class="glyphicon glyphicon-trash"></span>';
                            $hover_text = Yii::t('cruds', 'delete this record');
                            $options = [
                                'title' => $hover_text,
                                'aria-label' => $hover_text,
                                'data-pjax' => '0',
                            ];
                            return Html::a($label, $url, $options);
                        },
                    ],
                    'visibleButtons' => [
                        'view' => function ($model, $key, $index) {
                            /* @var $model Person */
                            return PersonAC::canView();
                        },
                        'update' => function ($model, $key, $index) {
                            /* @var $model Person */
                            return PersonAC::canUpdate();
                        },
                        'delete' => function ($model, $key, $index) {
                            /* @var $model Person */
                            return PersonAC::canDelete();
                        },
                    ],
                    'urlCreator' => function($action, $model, $key, $index) {
                        // using the column name as key, not mapping to 'id' like the standard generator
                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                        $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id.'/'.$action : $action;
                        return Url::toRoute($params);
                    },
                    'contentOptions' => ['nowrap' => 'nowrap']
                ],
            ],
        ]);
        ?>
    </div>

    <?php \yii\widgets\Pjax::end() ?>

</div>
