<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\actions\geographical_hierarchy\type\TypeMenu;
use app\models\geographical_hierarchy\Type;
use app\widgets\SplitDropdown;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\actions\geographical_hierarchy\type\TypeSearch */

$this->title = Yii::t('geographical_hierarchy','Type Archives');
$this->params['breadcrumbs'][] = Yii::t('geographical_hierarchy', 'Geographical Hierarchy');
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'Archives');
?>

<div class="giiant-crud type-archive">

    <div class="clearfix crud-navigation" style="padding-top: 30px;">
        <div class="pull-left">
            <h1 style="margin-top: 0;">
                <?= $searchModel->modelLabel(TRUE) ?>
                <small>
                    <?= Yii::t('cruds', 'Archives') ?>
                </small>
            </h1>
        </div>
        <div class="pull-right">
            <div>
                <?=
                SplitDropdown::widget([
                    'label' => TypeMenu::iconFor('create').'&nbsp; '.TypeMenu::labelFor('create'),
                    'encodeLabel' => FALSE,
                    'buttonAction' => 'create',
                    'options' => [
                        'class' => 'btn btn-primary',
                    ],
                    'dropdownActions' => [
                        'index',
                        'deleted',
                    ],
                    'dropdownButtons' => TypeMenu::dropdownButtons(),
                    'urlCreator' => function($action, $model) {
                        return TypeMenu::createUrlFor($action, $model);
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
                'description:ntext',
                [
                    'class' => \app\components\ActionColumn::class,
                    'contentRenderer' => function($model, $key, $index) {
                        return SplitDropdown::widget([
                                'model' => $model,
                                'label' => TypeMenu::iconFor('view').' '.TypeMenu::labelFor('view'),
                                'buttonAction' => 'view',
                                'dropdownActions' => [
                                    'view',
                                    'update',
                                    [
                                        'delete',
                                        'restore',
                                    ],
                                ],
                                'dropdownButtons' => TypeMenu::dropdownButtons(),
                                'urlCreator' => function($action, $model) {
                                    return TypeMenu::createUrlFor($action, $model);
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
