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
$this->title = Yii::t('<?= $subPath ? $subPath : 'pages' ?>','Update <?= $modelName ?>').' #'.$model->id;
<?php else: ?>
$this->title = Yii::t('<?= $subPath ? $subPath : 'pages' ?>','Update <?= $modelName ?>').' - '.$model-><?= $generator->getModelNameAttribute() ?>;
<?php endif; ?>
<?php if ($subPath): ?>
$this->params['breadcrumbs'][] = Yii::t('app', '<?= $subPath ?>');
<?php endif; ?>
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = <?= $generator->generateString('Edit') ?>;
?>
<div class="giiant-crud <?= $modelSlug ?>-update">

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
