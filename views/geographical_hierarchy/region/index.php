<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\actions\geographical_hierarchy\region\RegionMenu;
use app\models\geographical_hierarchy\Region;
use app\widgets\SplitDropdown;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\actions\geographical_hierarchy\region\RegionSearch */

$this->title = $searchModel->modelLabel(true);
$this->params['breadcrumbs'][] = Yii::t('geographical_hierarchy', 'Geographical Hierarchy');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="giiant-crud region-index">

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
                SplitDropdown::widget([
                    'label' => RegionMenu::iconFor('create').'&nbsp; '.RegionMenu::labelFor('create'),
                    'encodeLabel' => FALSE,
                    'buttonAction' => 'create',
                    'options' => [
                        'class' => 'btn btn-primary',
                    ],
                    'dropdownActions' => [
                        'create',
                    ],
                    'dropdownButtons' => RegionMenu::dropdownButtons(),
                    'urlCreator' => function($action, $model) {
                        return RegionMenu::createUrlFor($action, $model);
                    },
                ]);
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
                // generated by app\generators\crud\providers\RelationProvider::columnFormat
                [
                    'label' => $searchModel->attributeLabels()['country_id'],
                    'attribute' => 'countryName',
                    'format' => 'html',
                    'value' => function ($model) {
                        /* @var $model \app\models\geographical_hierarchy\Region */
                        return ArrayHelper::getValue($model, 'country.name');
                    },
                ],
                'reg_number',
                [
                    'class' => \app\components\ActionColumn::class,
                    'contentRenderer' => function($model, $key, $index) {
                        return SplitDropdown::widget([
                                'model' => $model,
                                'label' => RegionMenu::iconFor('view').' '.RegionMenu::labelFor('view'),
                                'buttonAction' => 'view',
                                'dropdownActions' => [
                                    'view',
                                    'update',
                                    [
                                        'delete',
                                    ],
                                ],
                                'dropdownButtons' => RegionMenu::dropdownButtons(),
                                'urlCreator' => function($action, $model) {
                                    return RegionMenu::createUrlFor($action, $model);
                                },
                        ]);
                    },
                ],
            ],
        ]);
        ?>
    </div>

    <?php \yii\widgets\Pjax::end() ?>

</div>