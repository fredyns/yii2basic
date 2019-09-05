<?php

use schmunk42\giiant\generators\model\Generator;
use app\generators\SaveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\form\Generator */

/*
 * JS for listbox "Saved Form"
 * on chenging listbox, form fill with selected saved forma data
 * currently work with input text, input checkbox and select form fields
 */
$this->registerJs(SaveForm::getSavedFormsJs($generator->getName()), yii\web\View::POS_END);
$this->registerJs(SaveForm::jsFillForm(), yii\web\View::POS_END);
echo $form->field($generator, 'savedForm')->dropDownList(
    SaveForm::getSavedFormsListbox($generator->getName()), ['onchange' => 'fillForm(this.value)']
);

echo $form->field($generator, 'tableName');
echo $form->field($generator, 'tablePrefix');
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'ns');
echo $form->field($generator, 'baseClass');
echo $form->field($generator, 'db');
echo $form->field($generator, 'generateRelations')->dropDownList([
    Generator::RELATIONS_NONE => Yii::t('giiant', 'No relations'),
    Generator::RELATIONS_ALL => Yii::t('giiant', 'All relations'),
    Generator::RELATIONS_ALL_INVERSE => Yii::t('giiant', 'All relations with inverse'),
]);
echo $form->field($generator, 'messageCategory');
