<?php

use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use app\actions\geographical_hierarchy\district\DistrictMenu;
use app\components\Tabs;
use app\models\geographical_hierarchy\District;
use app\widgets\SplitDropdown;

/* @var $this yii\web\View  */
/* @var $model District  */

$this->title = Yii::t('geographical_hierarchy', 'View District').' #'.$model->id;
$this->params['breadcrumbs'][] = Yii::t('geographical_hierarchy', 'Geographical Hierarchy');
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud district-view">

    <div class="clearfix crud-navigation" style="padding-top: 30px;">
        <div class="pull-left">
            <h1 style="margin-top: 0;">
                <?= $model->modelLabel() ?>
                <small>
                    #<?= $model->id ?>
                </small>
            </h1>
        </div>

        <!-- menu buttons -->
        <div class='pull-right'>
            <div>
                <?=
                SplitDropdown::widget([
                    'model' => $model,
                    'label' => DistrictMenu::iconFor('create').'&nbsp; '.DistrictMenu::labelFor('create'),
                    'encodeLabel' => FALSE,
                    'buttonAction' => 'update',
                    'dropdownActions' => [
                        'view',
                        [
                            'delete',
                        ],
                    ],
                    'dropdownButtons' => DistrictMenu::dropdownButtons(),
                    'urlCreator' => function($action, $model) {
                        return DistrictMenu::createUrlFor($action, $model);
                    },
                ]);
                ?>
            </div>
        </div>

    </div>

    <hr/>


    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'attribute' => 'city_id',
                'format' => 'html',
                'value' => ArrayHelper::getValue($model, 'city.name', '<span class="label label-warning">?</span>'),
            ],
            [
                'label' => Yii::t('geographical_hierarchy', 'Region'),
                'format' => 'html',
                'value' => ArrayHelper::getValue($model, 'city.region.name', '<span class="label label-warning">?</span>'),
            ],
            [
                'label' => Yii::t('geographical_hierarchy', 'Country'),
                'format' => 'html',
                'value' => ArrayHelper::getValue($model, 'city.country.name', '<span class="label label-warning">?</span>'),
            ],
            [
                'attribute' => 'type_id',
                'format' => 'html',
                'value' => ArrayHelper::getValue($model, 'type.name', '<span class="label label-warning">?</span>'),
            ],
            'reg_number',
        ],
    ]);
    ?>


    <hr/>
    <br/>
    <h3><?= Yii::t('geographical_hierarchy', 'Subdistricts') ?></h3>
    <div class="table-responsive">
        <?=
        \kartik\grid\GridView::widget([
            'layout' => '{summary}{pager}<br/>{items}{pager}',
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => $model->getSubdistricts(),
                'pagination' => [
                    'pageSize' => 20,
                    'pageParam' => 'page-subdistricts',
                ],
                ]),
            'columns' => [
                [
                    'class' => \kartik\grid\SerialColumn::class,
                ],
                'name',
                'reg_number',
            ],
        ]);
        ?>
    </div>
    <br/>
    <hr/>

</div>
