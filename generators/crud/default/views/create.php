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

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View  */
/* @var $model <?= $generator->modelClass ?>  */

$this->title = <?= $generator->generateString('New '.$modelName) ?>;
<?php if ($subPath): ?>
$this->params['breadcrumbs'][] = Yii::t('app', '<?= $subPath ?>');
<?php endif; ?>
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud <?= $modelSlug ?>-create">

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
            <?= '<?=' ?>Html::a('<span class="glyphicon glyphicon-remove"></span> '.<?= $generator->generateString('Cancel') ?>, Url::previous(), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr style="margin-top: 0;" />

    <?= '<?= ' ?>$this->render('_form', ['model' => $model]); ?>

</div>
