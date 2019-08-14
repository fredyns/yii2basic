<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\actions\geographical_hierarchy\subdistrict\SubdistrictMenu;
use app\models\geographical_hierarchy\Subdistrict;
use app\widgets\SplitDropdown;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\actions\geographical_hierarchy\subdistrict\SubdistrictSearch */

$this->title = $searchModel->modelLabel(true);
$this->params['breadcrumbs'][] = Yii::t('geographical_hierarchy', 'Geographical Hierarchy');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="giiant-crud subdistrict-index">

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
                    'label' => SubdistrictMenu::iconFor('create').'&nbsp; '.SubdistrictMenu::labelFor('create'),
                    'encodeLabel' => FALSE,
                    'buttonAction' => 'create',
                    'options' => [
                        'class' => 'btn btn-primary',
                    ],
                    'dropdownActions' => [
                        'create',
                    ],
                    'dropdownButtons' => SubdistrictMenu::dropdownButtons(),
                    'urlCreator' => function($action, $model) {
                        return SubdistrictMenu::createUrlFor($action, $model);
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
                [
                    'label' => $searchModel->attributeLabels()['district_id'],
                    'attribute' => 'districtName',
                    'format' => 'html',
                    'value' => function ($model) {
                        /* @var $model \app\models\geographical_hierarchy\Subdistrict */
                        return ArrayHelper::getValue($model, 'district.name');
                    },
                ],
                'reg_number',
                [
                    'class' => \app\components\ActionColumn::class,
                    'contentRenderer' => function($model, $key, $index) {
                        return SplitDropdown::widget([
                                'model' => $model,
                                'label' => SubdistrictMenu::iconFor('view').' '.SubdistrictMenu::labelFor('view'),
                                'buttonAction' => 'view',
                                'dropdownActions' => [
                                    'view',
                                    'update',
                                    [
                                        'delete',
                                    ],
                                ],
                                'dropdownButtons' => SubdistrictMenu::dropdownButtons(),
                                'urlCreator' => function($action, $model) {
                                    return SubdistrictMenu::createUrlFor($action, $model);
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
