<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this \yii\web\View  */
/* @var $generator \app\generators\crud\Generator  */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm  */
/* @var $searchModel <?= ltrim($generator->searchModelClass, '\\') ?> */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass), '-', true) ?>-search">

    <?= "<?php\n" ?>
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]);
    ?>

<?php
$count = 0;
foreach ($generator->getTableSchema()->getColumnNames() as $attribute) {
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

    <?= '<?php ' ?>ActiveForm::end(); ?>

</div>
