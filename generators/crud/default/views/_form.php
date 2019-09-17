<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this \yii\web\View  */
/* @var $generator \app\generators\crud\Generator  */
/* @var $tableSchema \yii\db\TableSchema  */
/* @var $softdelete bool  */
/* @var $modelClassName string  */
/* @var $modelSlug string  */
/* @var $modelName string  */
/* @var $model \yii\db\ActiveRecord  */
/* @var $searchClassName string search model class name w/o namespace  */
/* @var $acNameSpace string action control namespace */
/* @var $acClassName string action control class name w/o namespace */
/* @var $controllerClassName string  */
/* @var $controllerNameSpace string  */
/* @var $moduleNameSpace string  */
/* @var $moduleId string  */
/* @var $subPath string  */
/* @var $apiNameSpace string  */
/* @var $dateRange string[]  */
/* @var $timestampRange string[]  */

$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $tableSchema->columnNames;
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use cornernote\returnurl\ReturnUrl;
use app\components\Tabs;

/* @var $this yii\web\View  */
/* @var $form yii\widgets\ActiveForm  */
/* @var $model <?= $generator->modelClass ?>  */
?>

<div class="<?= Inflector::camel2id($modelClassName, '-', true) ?>-form">

    <?= "<?php\n" ?>
    $form = ActiveForm::begin([
            'id' => '<?= $model->formName() ?>',
            'layout' => '<?= $generator->formLayout ?>',
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
<?php
foreach ($safeAttributes as $attribute) {
    echo "\n".str_repeat(' ', 12)."<!-- attribute $attribute -->\n";
    $prepend = $generator->prependActiveField($attribute, $model);
    $field = $generator->activeField($attribute, $model);
    $append = $generator->appendActiveField($attribute, $model);

    if ($prepend) {
        echo str_repeat(' ', 12).str_replace("\n","\n".str_repeat(' ', 12),$prepend)."\n";
    }
    if (strpos($field,"\n")!==FALSE) {
        echo str_repeat(' ', 12)."<?=".str_replace("\n","\n".str_repeat(' ', 12),$field).'?>'."\n";
    } elseif ($field) {
        echo str_repeat(' ', 12)."<?= ".$field.' ?>'."\n";
    }
    if ($append) {
        echo str_repeat(' ', 12).str_replace("\n","\n".str_repeat(' ', 12),$append)."\n";
    }
}
?>

        </div>

        <hr/>

        <?= '<?= ' ?>$form->errorSummary($model); ?>

        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-2">

                <?= "<?=\n" ?>
                Html::submitButton(
                    '<span class="glyphicon glyphicon-check"></span> '
                    .($model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Save') ?>)
                    , [
                    'id' => 'save-'.$model->formName(),
                    'class' => 'btn btn-success'
                    ]
                );
                ?>

            </div>
        </div>

        <?= '<?php' ?> ActiveForm::end(); ?>

    </div>

</div>

