<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var app\models\sample\Person $model
*/

$this->title = Yii::t('sample', 'Person');
$this->params['breadcrumbs'][] = ['label' => Yii::t('sample', 'People'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud person-create">

    <h1>
        <?= Yii::t('sample', 'Person') ?>
        <small>
                        <?= Html::encode($model->name) ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?=             Html::a(
            Yii::t('cruds', 'Cancel'),
            \yii\helpers\Url::previous(),
            ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr />

    <?= $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
