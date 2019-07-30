<?php

use yii\helpers\StringHelper;

/* @var $this \yii\web\View  */
/* @var $generator \app\generators\crud\Generator  */
/* @var $model \yii\db\ActiveRecord  */

/** @var \yii\db\ActiveRecord $model */
## TODO: move to generator (?); cleanup
$model = new $generator->modelClass();
$model->setScenario('crud');
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $model->setScenario('default');
    $safeAttributes = $model->safeAttributes();
}
if (empty($safeAttributes)) {
    $safeAttributes = $model->getTableSchema()->columnNames;
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use dmstr\bootstrap\Tabs;

/* @var $this yii\web\View  */
/* @var $form yii\widgets\ActiveForm  */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?>  */
?>

<div class="<?= \yii\helpers\Inflector::camel2id(StringHelper::basename($generator->modelClass), '-', true) ?>-form">

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
        echo str_repeat(' ', 12).$prepend."\n";
    }
    if (strpos($field,"\n")!==FALSE) {
        echo str_repeat(' ', 12)."<?=".str_replace("\n","\n".str_repeat(' ', 12),$field).'?>'."\n";
    } elseif ($field) {
        echo str_repeat(' ', 12)."<?= ".$field.' ?>'."\n";
    }
    if ($append) {
        echo str_repeat(' ', 12).$append."\n";
    }
}
?>

        </div>

        <hr/>

        <?= '<?= ' ?>$form->errorSummary($model); ?>

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

        <?= '<?php ' ?>ActiveForm::end(); ?>

    </div>

</div>

