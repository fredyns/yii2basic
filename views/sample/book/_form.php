<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use cornernote\returnurl\ReturnUrl;
use app\components\Tabs;

/* @var $this yii\web\View  */
/* @var $form yii\widgets\ActiveForm  */
/* @var $model app\models\sample\Book  */
?>

<div class="book-form">

    <?php
    $form = ActiveForm::begin([
            'id' => 'Book',
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

            <!-- attribute title -->
            <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>

            <!-- attribute description -->
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            <!-- attribute author_id -->
            <?=
            // generated by app\generators\crud\providers\RelationProvider::activeField
                $form
                ->field($model, 'author_id')
                ->widget(\kartik\select2\Select2::class, [
                    'initValueText' => \yii\helpers\ArrayHelper::getValue($model, 'author.name', $model->author_id),
                    'options' => ['placeholder' => Yii::t('app', 'searching...')],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'tags' => false,
                        'minimumInputLength' => 2,
                        'language' => [
                            'errorLoading' => new \yii\web\JsExpression('function () { return "'.Yii::t('cruds', 'waiting results...').'"; }'),
                        ],
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['/api/sample/person/select2-options']),
                            'dataType' => 'json',
                            'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new \yii\web\JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new \yii\web\JsExpression('function(item) { return item.text; }'),
                        'templateSelection' => new \yii\web\JsExpression('function (item) { return item.text; }'),
                    ],
                ])
            ;
            ?>

            <!-- attribute editor_id -->
            <?=
            // generated by app\generators\crud\providers\RelationProvider::activeField
                $form
                ->field($model, 'editor_id')
                ->widget(\kartik\select2\Select2::class, [
                    'initValueText' => \yii\helpers\ArrayHelper::getValue($model, 'editor.name', $model->editor_id),
                    'options' => ['placeholder' => Yii::t('app', 'searching...')],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'tags' => false,
                        'minimumInputLength' => 2,
                        'language' => [
                            'errorLoading' => new \yii\web\JsExpression('function () { return "'.Yii::t('cruds', 'waiting results...').'"; }'),
                        ],
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['/api/sample/person/select2-options']),
                            'dataType' => 'json',
                            'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new \yii\web\JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new \yii\web\JsExpression('function(item) { return item.text; }'),
                        'templateSelection' => new \yii\web\JsExpression('function (item) { return item.text; }'),
                    ],
                ])
            ;
            ?>

            <!-- attribute released_date -->
            <?=
            // generated by app\generators\crud\providers\DateProvider::activeField
                $form
                ->field($model, 'released_date')
                ->widget(\kartik\date\DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd'
                    ],
                ])
            ;
            ?>

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

    </div>

    <?php ActiveForm::end(); ?>

</div>
