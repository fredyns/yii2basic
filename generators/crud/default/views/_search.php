<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this \yii\web\View  */
/* @var $generator \app\generators\crud\Generator  */
/* @var $tableSchema \yii\db\TableSchema  */
/* @var $giiConfigs array  */
/* @var $softdelete bool  */
/* @var $modelClassName string  */
/* @var $modelSlug string  */
/* @var $modelName string  */
/* @var $model \yii\db\ActiveRecord  */
/* @var $controllerClassName string  */
/* @var $controllerNameSpace string  */
/* @var $moduleNameSpace string  */
/* @var $subPath string  */
/* @var $actionParentNameSpace string  */
/* @var $actionParent string[]  */
/* @var $apiNameSpace string  */
/* @var $menuNameSpace string  */
/* @var $dateRange string[]  */
/* @var $timestampRange string[]  */

echo '<?php\n';
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm  */
/* @var $searchModel <?= $generator->searchModelClass ?> */
?>

<div class="<?= Inflector::camel2id($modelClassName, '-', true) ?>-search">

    <?= "<?php\n" ?>
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]);
    ?>

<?php
$count = 0;
foreach ($tableSchema->getColumnNames() as $attribute) {
    if (++$count < 6) {
        echo str_repeat(' ', 8)."<?= ".$generator->generateActiveSearchField($attribute)." ?>\n\n";
    } else {
        echo str_repeat(' ', 8)."<?php // echo ".$generator->generateActiveSearchField($attribute)." ?>\n\n";
    }
}
?>
    <div class="form-group">
        <?= '<?= ' ?>Html::submitButton(<?= $generator->generateString('Search') ?>, ['class' => 'btn btn-primary']) ?>
        <?= '<?= ' ?>Html::resetButton(<?= $generator->generateString('Reset') ?>, ['class' => 'btn btn-default']) ?>
    </div>

    <?= '<?php' ?> ActiveForm::end(); ?>

</div>
