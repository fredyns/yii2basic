<?php

use yii\helpers\ArrayHelper;
use app\generators\modelclass\Generator;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator Generator */
?>

<div class="panel panel-default">
    <div class="panel-heading">Costum namepaces for each table</div>
    <div class="panel-body">
        <?php foreach ($generator->getTableNames() as $tableName): ?>
            <div class="form-group field-generator-modelClasses">
                <label 
                    class="control-label help" 
                    data-toggle="popover" 
                    data-content="Custom namespace for table '<?= $tableName ?>'" 
                    data-placement="right" 
                    for="generator-modelClasses-<?= $tableName ?>"
                    >
                        <?= $tableName ?>
                </label>
                <input 
                    type="text" 
                    id="generator-modelClasses-<?= $tableName ?>" 
                    class="form-control" 
                    name="Generator[modelClasses][<?= $tableName ?>]" 
                    value="<?= ArrayHelper::getValue($generator->modelClasses, $tableName) ?>"
                    />

                <div class="invalid-feedback"></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<hr/>