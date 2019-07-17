<?php

use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/* @var $this yii\web\View  */
/* @var $model app\models\sample\Person  */

$this->title = $model->modelLabel();
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud person-view">

    <div class="clearfix crud-navigation" style="padding-top: 30px;">
        <div class="pull-left">
            <h1 style="margin-top: 0;">
                <?= $model->modelLabel() ?>
                <small>
                    #<?= $model->id ?>
                    <?php if ($model->is_deleted): ?>
                        <span class="badge">deleted</span>
                    <?php endif; ?>
                </small>
            </h1>
        </div>
        <!-- menu buttons -->
        <div class='pull-right'>
            <?=
            ButtonDropdown::widget([
                'label' => Yii::t('cruds', 'Edit'),
                'tagName' => 'a',
                'split' => true,
                'options' => [
                    'href' => ['update', 'id' => $model->id],
                    'class' => 'btn btn-info',
                ],
                'dropdown' => [
                    'encodeLabels' => FALSE,
                    'options' => [
                        'class' => 'dropdown-menu-right',
                    ],
                    'items' => [
                        '<li role="presentation" class="divider"></li>',
                        [
                            'label' => '<span class="glyphicon glyphicon-list"></span> '.Yii::t('cruds', 'Full list'),
                            'url' => ['index'],
                        ],
                        [
                            'label' => '<span class="glyphicon glyphicon-plus"></span> '.Yii::t('cruds', 'New'),
                            'url' => ['create'],
                        ],
                        '<li role="presentation" class="divider"></li>',
                        [
                            'label' => '<span class="glyphicon glyphicon-trash"></span> '.Yii::t('cruds', 'Delete'),
                            'url' => ['delete', 'id' => $model->id],
                            'linkOptions' => [
                                'data-confirm' => Yii::t('cruds', 'Are you sure to delete this item?'),
                                'data-method' => 'post',
                                'data-pjax' => FALSE,
                                'class' => 'label label-danger',
                            ],
                            'visible' => ($model->is_deleted == FALSE),
                        ],
                        [
                            'label' => '<span class="glyphicon glyphicon-floppy-open"></span> '.Yii::t('cruds', 'Restore'),
                            'url' => ['delete', 'id' => $model->id],
                            'linkOptions' => [
                                'data-confirm' => Yii::t('cruds', 'Are you sure to restore this item?'),
                                'data-method' => 'post',
                                'data-pjax' => FALSE,
                                'class' => 'label label-info',
                            ],
                            'visible' => ($model->is_deleted),
                        ],
                    ],
                ],
            ]);
            ?>
        </div>

    </div>

    <hr/>

    
    <?= 
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
        ],
    ]); 
    ?>

    
    <hr/>


    <hr/>

    <div style="font-size: 75%; font-style: italic;">
        <?= Yii::t('timestamp', 'Created') ?>
        <?= Yii::$app->formatter->asDate($model->created_at, "d MMMM Y '".Yii::t('timestamp', 'at')."' HH:mm") ?>
        <?= Yii::t('timestamp', 'by') ?>
        <?= ArrayHelper::getValue($model, 'createdBy.username', '-') ?>
        <br/>
        <?= Yii::t('timestamp', 'Updated') ?>
        <?= Yii::$app->formatter->asDate($model->updated_at, "d MMMM Y '".Yii::t('timestamp', 'at')."' HH:mm") ?>
        <?= Yii::t('timestamp', 'by') ?>
        <?= ArrayHelper::getValue($model, 'updatedBy.username', '-') ?>
        <?php if ($model->is_deleted): ?>
            <br/>
            <?= Yii::t('timestamp', 'Deleted') ?>
            <?= Yii::$app->formatter->asDate($model->deleted_at, "d MMMM Y '".Yii::t('timestamp', 'at')."' HH:mm") ?>
            <?= Yii::t('timestamp', 'by') ?>
            <?= ArrayHelper::getValue($model, 'deletedBy.username', '-') ?>
        <?php endif; ?>
    </div>

</div>
