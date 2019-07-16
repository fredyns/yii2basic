<?php

use yii\helpers\ArrayHelper;
use app\generators\modelmeta\Generator;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator Generator */

echo $form->field($generator, 'tablePrefix');
echo $form->field($generator, 'baseClass');
echo $form->field($generator, 'db');
echo $form->field($generator, 'generateRelations')->dropDownList([
    Generator::RELATIONS_NONE => Yii::t('giiant', 'No relations'),
    Generator::RELATIONS_ALL => Yii::t('giiant', 'All relations'),
    Generator::RELATIONS_ALL_INVERSE => Yii::t('giiant', 'All relations with inverse'),
]);
echo $form->field($generator, 'generateLabelsFromComments')->checkbox();
echo $form->field($generator, 'generateHintsFromComments')->checkbox();
echo $form->field($generator, 'generateQuery')->checkbox();
echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'singularEntities')->checkbox();
echo $form->field($generator, 'messageCategory');
?>

<div class="panel panel-default">
    <div class="panel-heading">Costum namepaces for each table</div>
    <div class="panel-body">
        <?php foreach ($generator->getTableNames() as $tableName): ?>
            <div class="form-group field-generator-nameSpaces">
                <label 
                    class="control-label help" 
                    data-toggle="popover" 
                    data-content="Custom namespace for table '<?= $tableName ?>'" 
                    data-placement="right" 
                    for="generator-nameSpaces-<?= $tableName ?>"
                    >
                        <?= $tableName ?>
                </label>
                <input 
                    type="text" 
                    id="generator-nameSpaces-<?= $tableName ?>" 
                    class="form-control" 
                    name="Generator[nameSpaces][<?= $tableName ?>]" 
                    value="<?= ArrayHelper::getValue($generator->nameSpaces, $tableName, ArrayHelper::getValue($generator->metadata, $tableName.'.nameSpace', $generator->ns)) ?>"
                    />

                <div class="invalid-feedback"></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<hr/>