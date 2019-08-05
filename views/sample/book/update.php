<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View  */
/* @var $model app\models\sample\Book  */

$this->title = $model->modelLabel();
$this->params['breadcrumbs'][] = Yii::t('app', 'sample');
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'Edit');
?>
<div class="giiant-crud book-update">

    <div class="clearfix crud-navigation" style="padding-top: 30px;">
        <div class="pull-left">
            <h1 style="margin-top: 0;">
                <?= $model->modelLabel() ?>
                <small>
                    #<?= $model->id ?>
                </small>
            </h1>
        </div>
        <div class="pull-right">
            <?= Html::a('<span class="glyphicon glyphicon-file"></span> '.Yii::t('cruds', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-remove"></span> '.Yii::t('cruds', 'Cancel'), Url::previous(), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr />

    <?= $this->render('_form', ['model' => $model]); ?>

</div>