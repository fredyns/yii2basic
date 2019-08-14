<?php

use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use app\actions\geographical_hierarchy\subdistrict\SubdistrictMenu;
use app\components\Tabs;
use app\models\geographical_hierarchy\Subdistrict;
use app\widgets\SplitDropdown;

/* @var $this yii\web\View  */
/* @var $model Subdistrict  */

$this->title = Yii::t('geographical_hierarchy','View Subdistrict').' #'.$model->id;
$this->params['breadcrumbs'][] = Yii::t('geographical_hierarchy', 'Geographical Hierarchy');
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud subdistrict-view">

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
                    'label' => SubdistrictMenu::iconFor('create').'&nbsp; '.SubdistrictMenu::labelFor('create'),
                    'encodeLabel' => FALSE,
                    'buttonAction' => 'update',
                    'dropdownActions' => [
                        'view',
                        [
                            'delete',
                        ],
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

    <hr/>

    
    <?= 
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'attribute' => 'district_id',
                'format' => 'html',
                'value' => ArrayHelper::getValue($model, 'district.name', '<span class="label label-warning">?</span>'),
            ],
            [
                'label' => Yii::t('geographical_hierarchy', 'City'),
                'format' => 'html',
                'value' => ArrayHelper::getValue($model, 'district.city.name', '<span class="label label-warning">?</span>'),
            ],
            [
                'label' => Yii::t('geographical_hierarchy', 'Region'),
                'format' => 'html',
                'value' => ArrayHelper::getValue($model, 'district.city.region.name', '<span class="label label-warning">?</span>'),
            ],
            [
                'label' => Yii::t('geographical_hierarchy', 'Country'),
                'format' => 'html',
                'value' => ArrayHelper::getValue($model, 'district.city.country.name', '<span class="label label-warning">?</span>'),
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
    <hr/>

</div>
