<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use cornernote\returnurl\ReturnUrl;
use app\components\Tabs;

/* @var $this yii\web\View  */
/* @var $form yii\widgets\ActiveForm  */
/* @var $model app\models\geo_address\Type  */
?>

<div class="type-form">

    <?php
    $form = ActiveForm::begin([
            'id' => 'Type',
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-danger',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    #'offset' => 'col-sm-offset-4',
                    'wrapper' => 'col-sm-8',
                    'error' => '',
                    'hint' => '',
                ],
            ],
    ]);
    echo Html::hiddenInput('ru', ReturnUrl::getRequestToken());
    ?>

    <div class="">
        <div class="">

            <!-- attribute name -->
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <!-- attribute description -->
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        </div>

        <hr/>

        <?= $form->errorSummary($model); ?>

        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-2">

                <?=
                Html::submitButton(
                    '<span class="glyphicon glyphicon-check"></span> '
                    .($model->isNewRecord ? Yii::t('cruds', 'Create') : Yii::t('cruds', 'Save'))
                    , [
                    'id' => 'save-'.$model->formName(),
                    'class' => 'btn btn-success'
                    ]
                );
                ?>

            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>

