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

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Url;
use cornernote\returnurl\ReturnUrl;

/* @var $this yii\web\View  */
/* @var $model <?= $generator->modelClass ?>  */

$this->title = Yii::t('<?= trim($moduleId.'/'.$subPath, '/') ?>','New <?= $modelName ?>');
<?php if ($moduleId != 'app'): ?>
$this->params['breadcrumbs'][] = Yii::t('<?= $moduleId ?>', '<?= Inflector::camel2words($moduleId) ?>');
<?php endif; ?>
<?php if ($subPath): ?>
$this->params['breadcrumbs'][] = Yii::t('<?= $moduleId.'/'.$subPath ?>', '<?= Inflector::camel2words($subPath) ?>');
<?php endif; ?>
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-crud <?= $modelSlug ?>-create">

    <div class="clearfix crud-navigation" style="padding-top: 30px;">
        <div class="pull-left">
            <h1 style="margin-top: 0;">
                <?= '<?=' ?> $model->modelLabel() ?>
                <small>
                    <?= '<?= '.$generator->generateString('New') ?> ?>
                </small>
            </h1>
        </div>
        <div class="pull-right">
            <?= '<?=' ?> Html::a('<span class="glyphicon glyphicon-remove"></span> '.<?= $generator->generateString('Cancel') ?>, ReturnUrl::getUrl(Url::previous()), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr style="margin-top: 0;" />

    <?= '<?= ' ?>$this->render('_form', ['model' => $model]); ?>

</div>
