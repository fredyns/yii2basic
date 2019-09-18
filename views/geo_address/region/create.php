<?php

use yii\helpers\Html;
use yii\helpers\Url;
use cornernote\returnurl\ReturnUrl;

/* @var $this yii\web\View  */
/* @var $model app\models\geo_address\Region  */

$this->title = Yii::t('app/geo_address/texts','New Region');
$this->params['breadcrumbs'][] = Yii::t('app/geo_address/texts', 'Geo Address');
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-crud region-create">

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
            <?= Html::a('<span class="glyphicon glyphicon-remove"></span> '.Yii::t('cruds', 'Cancel'), ReturnUrl::getUrl(Url::previous()), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr style="margin-top: 0;" />

    <?= $this->render('_form', ['model' => $model]); ?>

</div>
