<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this \yii\web\View  */
/* @var $generator \app\generators\crud\Generator  */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

/** @var \yii\db\ActiveRecord $model */
$model = new $generator->modelClass();
$model->setScenario('crud');

$modelName = Inflector::camel2words(Inflector::pluralize(StringHelper::basename($model::className())));

$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    /** @var \yii\db\ActiveRecord $model */
    $model = new $generator->modelClass();
    $safeAttributes = $model->safeAttributes();
    if (empty($safeAttributes)) {
        $safeAttributes = $model->getTableSchema()->columnNames;
    }
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Url;
use <?= $generator->indexWidgetType === 'grid' ? $generator->indexGridClass : 'yii\\widgets\\ListView' ?>;
use <?= ltrim($generator->modelClass, '\\') ?>;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel <?= ltrim($generator->searchModelClass, '\\') ?> */

$this->title = $searchModel->modelLabel(true);
$this->params['breadcrumbs'][] = $this->title;
$actionColumnTemplateString = "<div class=\"action-buttons\">{view} {update} {delete}</div>";
<?= '?>\n';?>

<div class="giiant-crud <?= Inflector::camel2id(StringHelper::basename($generator->modelClass), '-', true) ?>-index">

    <div class="clearfix crud-navigation" style="padding-top: 30px;">
        <div class="pull-left">
            <h1 style="margin-top: 0;">
                <?= '<?=' ?> $searchModel->modelLabel(TRUE) ?>
                <small>
                    <?= '<?= '.$generator->generateString('List') ?> ?>
                </small>
            </h1>
        </div>
        <div class="pull-right">
            <?= '<?= ' ?>Html::a('<span class="glyphicon glyphicon-plus"></span> '.<?=$generator->generateString('New')?>, ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?= '<?php' ?> //= $this->render('_search', ['model' => $searchModel]); ?>

    <hr style="margin-top: 0;" />

    <?= "<?php\n" ?>
    \yii\widgets\Pjax::begin([
        'id' => 'pjax-main',
        'enableReplaceState' => false,
        'linkSelector' => '#pjax-main ul.pagination a, th a',
        'clientOptions' => [
            'pjax:success' => 'function(){alert("yo")}',
        ],
    ]);
    ?>

<?php if ($generator->indexWidgetType === 'grid'): ?>
    <div class="table-responsive">
        <?= "<?=\n" ?>
        GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => [
                'class' => yii\widgets\LinkPager::class,
                'firstPageLabel' => <?= $generator->generateString('First') ?>,
                'lastPageLabel' => <?= $generator->generateString('Last').",\n" ?>
            ],
<?php if ($generator->searchModelClass !== ''): ?>
            'filterModel' => $searchModel,
<?php endif; ?>
            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
            'headerRowOptions' => ['class' => 'x'],
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                ],
<?php
$count = 0;
foreach ($safeAttributes as $attribute) {
    $format = trim($generator->columnFormat($attribute, $model));
    if ($format == false) {
        continue;
    }
    if (++$count < $generator->gridMaxColumns) {
        echo str_repeat(' ', 16).str_replace("\n", "\n".str_repeat(' ', 16), $format) . ",\n";
    } else {
        echo str_repeat(' ', 16)."/* //\n"
            .str_repeat(' ', 16).str_replace("\n", "\n".str_repeat(' ', 16), $format).",\n"
            .str_repeat(' ', 16)."// */\n";
    }
}
?>
                [
                    'class' => '<?= $generator->actionButtonClass ?>',
                    'template' => $actionColumnTemplateString,
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            $options = [
                                'title' => Yii::t('<?=$generator->messageCategory?>', 'View'),
                                'aria-label' => Yii::t('<?=$generator->messageCategory?>', 'View'),
                                'data-pjax' => '0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-file"></span>', $url, $options);
                        }
                    ],
                    'urlCreator' => function($action, $model, $key, $index) {
                        // using the column name as key, not mapping to 'id' like the standard generator
                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                        $params[0] = Yii::$app->controller->id ? Yii::$app->controller->id.'/'.$action : $action;
                        return Url::toRoute($params);
                    },
                    'contentOptions' => ['nowrap' => 'nowrap']
                ],
            ],
        ]);
        ?>
    </div>

<?php else: ?>
    <?= "<?= \n" ?>
    ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]);
    ?>

<?php endif; ?>
    <?= "<?php \yii\widgets\Pjax::end() ?>\n"; ?>

</div>