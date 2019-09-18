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
/* @var $messageCategory string  */
/* @var $apiNameSpace string  */
/* @var $dateRange string[]  */
/* @var $timestampRange string[]  */

$urlParams = $generator->generateUrlParams();
$haveID = ($tableSchema->getColumn('id') !== null);

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Url;
use cornernote\returnurl\ReturnUrl;

/* @var $this yii\web\View  */
/* @var $model <?= $generator->modelClass ?>  */

<?php if ($haveID): ?>
$this->title = Yii::t('<?= $messageCategory ?>','View <?= $modelName ?>').' #'.$model->id;
<?php else: ?>
$this->title = Yii::t('<?= $messageCategory ?>','View <?= $modelName ?>').' - '.$model-><?= $generator->getModelNameAttribute() ?>;
<?php endif; ?>
<?php if ($moduleId != 'app'): ?>
$this->params['breadcrumbs'][] = Yii::t('<?= $moduleId.'/texts' ?>', '<?= Inflector::camel2words($moduleId) ?>');
<?php endif; ?>
<?php if ($subPath): ?>
$this->params['breadcrumbs'][] = Yii::t('<?= $messageCategory ?>', '<?= Inflector::camel2words($subPath) ?>');
<?php endif; ?>
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = <?= $generator->generateString('Edit') ?>;
?>
<div class="app-crud <?= $modelSlug ?>-update">

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
            <?= '<?=' ?> Html::a('<span class="glyphicon glyphicon-remove"></span> '.<?= $generator->generateString('Cancel') ?>, ReturnUrl::getUrl(Url::previous()), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr />

    <?= '<?=' ?> $this->render('_form', ['model' => $model]); ?>

</div>
