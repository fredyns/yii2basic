<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View  */
/* @var $model app\models\sample\Person  */

$this->title = $model->modelLabel();
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud person-create">

    <div class="clearfix crud-navigation" style="padding-top: 30px;">
        <div class="pull-left">
            <h1 style="margin-top: 0;">
                <?= $model->modelLabel() ?>
                <small>
                    <?= Yii::t('cruds', 'New') ?>
                </small>
            </h1>
        </div>
        <div class="pull-right">
            <?=Html::a('<span class="glyphicon glyphicon-remove"></span> '.Yii::t('cruds', 'Cancel'), Url::previous(), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr style="margin-top: 0;" />

    <?= $this->render('_form', ['model' => $model]); ?>

</div>