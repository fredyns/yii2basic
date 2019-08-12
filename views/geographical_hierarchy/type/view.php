<?php

use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use app\actions\geographical_hierarchy\type\TypeMenu;
use app\components\Tabs;
use app\models\geographical_hierarchy\Type;
use app\widgets\SplitDropdown;

/* @var $this yii\web\View  */
/* @var $model Type  */

$this->title = Yii::t('geographical_hierarchy','View Type').' #'.$model->id;
$this->params['breadcrumbs'][] = Yii::t('geographical_hierarchy', 'Geographical Hierarchy');
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud type-view">

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
                    'label' => TypeMenu::iconFor('create').'&nbsp; '.TypeMenu::labelFor('create'),
                    'encodeLabel' => FALSE,
                    'buttonAction' => 'update',
                    'dropdownActions' => [
                        'view',
                        [
                            'delete',
                        ],
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

    <hr/>

    
    <?= 
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description:ntext',
        ],
    ]);
    ?>

    
    <hr/>
    <br/>
    <hr/>

</div>
