<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm  */
/* @var $searchModel app\lib\sample\book\BookSearch */
?>

<div class="book-search">

    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]);
    ?>

        <?= $form->field($model, 'id') ?>

        <?= $form->field($model, 'created_at') ?>

        <?= $form->field($model, 'created_by') ?>

        <?= $form->field($model, 'updated_at') ?>

        <?= $form->field($model, 'updated_by') ?>

        <?php // echo $form->field($model, 'is_deleted') ?>

        <?php // echo $form->field($model, 'deleted_at') ?>

        <?php // echo $form->field($model, 'deleted_by') ?>

        <?php // echo $form->field($model, 'title') ?>

        <?php // echo $form->field($model, 'description') ?>

        <?php // echo $form->field($model, 'author_id') ?>

        <?php // echo $form->field($model, 'editor_id') ?>

        <?php // echo $form->field($model, 'released_date') ?>

    <div class="form-group">
        <div class="col-sm-8 col-sm-offset-2">
            <?= Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
