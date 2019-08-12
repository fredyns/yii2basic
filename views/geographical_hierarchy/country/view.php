<?php

use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use app\actions\geographical_hierarchy\country\CountryMenu;
use app\components\Tabs;
use app\models\geographical_hierarchy\Country;
use app\widgets\SplitDropdown;

/* @var $this yii\web\View  */
/* @var $model Country  */

$this->title = Yii::t('geographical_hierarchy','View Country').' #'.$model->id;
$this->params['breadcrumbs'][] = Yii::t('geographical_hierarchy', 'Geographical Hierarchy');
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud country-view">

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
                    'label' => CountryMenu::iconFor('create').'&nbsp; '.CountryMenu::labelFor('create'),
                    'encodeLabel' => FALSE,
                    'buttonAction' => 'update',
                    'dropdownActions' => [
                        'view',
                        [
                            'delete',
                        ],
                    ],
                    'dropdownButtons' => CountryMenu::dropdownButtons(),
                    'urlCreator' => function($action, $model) {
                        return CountryMenu::createUrlFor($action, $model);
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
            'code',
        ],
    ]);
    ?>

    
    <hr/>
    <br/>
    <h3><?= Yii::t('geographical_hierarchy', 'Regions') ?></h3>
    <div class="table-responsive">
        <?=
        \kartik\grid\GridView::widget([
            'layout' => '{summary}{pager}<br/>{items}{pager}',
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => $model->getRegions(),
                'pagination' => [
                    'pageSize' => 50,
                    'pageParam' => 'page-regions',
                ],
            ]),
            'columns' => [
                [
                    'class' => \kartik\grid\SerialColumn::class,
                ],
                'name',
                // generated by app\generators\crud\providers\RelationProvider::columnFormat
                [
                    'attribute' => 'country_id',
                    'format' => 'html',
                    'value' => function ($model) {
                        /* @var $model \app\models\geographical_hierarchy\Region */
                        return ArrayHelper::getValue($model, 'country.name');
                    },
                ],
                'reg_number',
            ],
        ]);
        ?>
    </div>
    <br/>
    <hr/>

</div>