<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this \yii\web\View  */
/* @var $generator \app\generators\crud\Generator  */
/* @var $model \yii\db\ActiveRecord  */

$urlParams = $generator->generateUrlParams();
$model = new $generator->modelClass();
$model->setScenario('crud');
$className = $model::className();
$modelName = Inflector::camel2words(StringHelper::basename($model::className()));
$tableSchema = $generator->getTableSchema();
$haveID=($tableSchema->getColumn('id') !== null);

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View  */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?>  */

$this->title = $model->modelLabel();
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = <?= $generator->generateString('Edit') ?>;
?>
<div class="giiant-crud <?= Inflector::camel2id(StringHelper::basename($generator->modelClass), '-', true) ?>-update">

    <div class="clearfix crud-navigation" style="padding-top: 30px;">
        <div class="pull-left">
            <h1 style="margin-top: 0;">
                <?= '<?= '?>$model->modelLabel() ?>
                <small>
<?php
if ($haveID) {
    echo str_repeat(' ', 20)."#<?= \$model->id ?>\n";
} else {
    $label = StringHelper::basename($generator->modelClass);
    echo str_repeat(' ', 20)."<?= Html::encode(\$model->".$generator->getModelNameAttribute().") ?>\n";
}
?>
                </small>
            </h1>
        </div>
        <div class="pull-right">
            <?= '<?=' ?> Html::a('<span class="glyphicon glyphicon-file"></span> '.<?= $generator->generateString('View') ?>, ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
            <?= '<?=' ?> Html::a('<span class="glyphicon glyphicon-remove"></span> '.<?= $generator->generateString('Cancel') ?>, Url::previous(), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr />

    <?= '<?=' ?> $this->render('_form', ['model' => $model]); ?>

</div>