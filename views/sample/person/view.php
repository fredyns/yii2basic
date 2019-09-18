<?php

use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use cornernote\returnurl\ReturnUrl;
use app\lib\sample\person\PersonAC;
use app\models\sample\Person;

/* @var $this yii\web\View  */
/* @var $model Person  */

$this->title = Yii::t('app/sample/texts','View Person').' #'.$model->id;
$this->params['breadcrumbs'][] = Yii::t('app/sample/texts', 'Sample');
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="app-crud person-view">

    <div class="clearfix crud-navigation" style="padding-top: 30px;">
        <div class="pull-left">
            <h1 style="margin-top: 0;">
                <?= $model->modelLabel() ?>
                <small>
                    #<?= $model->id ?>
                    <?php if ($model->is_deleted): ?>
                        <span class="badge">Yii::t('cruds', 'deleted')</span>
                    <?php endif; ?>
                </small>
            </h1>
        </div>

        <!-- menu buttons -->
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
                                'label' => '<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('cruds', "Edit"),
                                'encode' => FALSE,
                                'url' => [
                                    'update',
                                    'id' => $model->id,
                                    'ru' => ReturnUrl::getToken(),
                                ],
                                'visible' => PersonAC::canUpdate(),
                            ],
                            //'<li role="presentation" class="divider"></li>',
                        ],
                    ],
                ])
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
        ],
    ]);
    ?>

    
    <hr/>
    <br/>
    <hr/>

    <div class="clearfix">
        <!-- danger menu buttons -->
        <div class='pull-right'>
            <div>
                <?php
                if (PersonAC::canDelete()) {
                    $label = '<span class="glyphicon glyphicon-trash"></span> '.Yii::t('cruds', 'Delete');
                    $options = [
                        'class' => 'btn btn-danger',
                        'title' => Yii::t('cruds', 'Delete'),
                        'aria-label' => Yii::t('cruds', 'Delete'),
                        'data-confirm' => Yii::t('cruds', 'Are you sure to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => FALSE,
                    ];
                    $url = [
                        'delete',
                        'id' => $model->id,
                        'ru' => ReturnUrl::urlToToken(Url::to(['index'])),
                    ];
                    echo Html::a($label, $url, $options);
                }
                ?>
            </div>
            <div>
                <?php
                if (PersonAC::canRestore()) {
                    $label = '<span class="glyphicon glyphicon-refresh"></span> '.Yii::t('cruds', 'Restore');
                    $options = [
                        'class' => 'btn btn-warning',
                        'title' => Yii::t('cruds', 'Restore'),
                        'aria-label' => Yii::t('cruds', 'Restore'),
                        'data-confirm' => Yii::t('cruds', 'Are you sure to restore this item?'),
                        'data-method' => 'post',
                        'data-pjax' => FALSE,
                    ];
                    $url = [
                        'restore',
                        'id' => $model->id,
                        'ru' => ReturnUrl::urlToToken(Url::to(['view', 'id' => $model->id])),
                    ];
                    echo Html::a($label, $url, $options);
                }
                ?>
            </div>
        </div>
    </div>


    <div style="font-size: 75%; font-style: italic;">
        <?= Yii::t('record-info', 'Created') ?>
        <?= Yii::$app->formatter->asDate($model->created_at, "eeee, d MMMM Y '".Yii::t('record-info', 'at')."' HH:mm") ?>
        <?= Yii::t('record-info', 'by') ?>
        <?= ArrayHelper::getValue($model, 'createdBy.username', Yii::t('app', 'Guest')) ?>
        <br/>
        <?= Yii::t('record-info', 'Updated') ?>
        <?= Yii::$app->formatter->asDate($model->updated_at, "eeee, d MMMM Y '".Yii::t('record-info', 'at')."' HH:mm") ?>
        <?= Yii::t('record-info', 'by') ?>
        <?= ArrayHelper::getValue($model, 'updatedBy.username', Yii::t('app', 'Guest')) ?>
        <?php if ($model->is_deleted): ?>
            <br/>
            <?= Yii::t('record-info', 'Deleted') ?>
            <?= Yii::$app->formatter->asDate($model->deleted_at, "eeee, d MMMM Y '".Yii::t('record-info', 'at')."' HH:mm") ?>
            <?= Yii::t('record-info', 'by') ?>
            <?= ArrayHelper::getValue($model, 'deletedBy.username', Yii::t('app', 'Guest')) ?>
        <?php endif; ?>
    </div>

</div>
