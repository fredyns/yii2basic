<?php

use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use app\actions\sample\person\PersonMenu;
use app\components\Tabs;
use app\models\sample\Person;
use app\widgets\SplitDropdown;

/* @var $this yii\web\View  */
/* @var $model Person  */

$this->title = Yii::t('cruds', 'View Person').' #'.$model->id;
$this->params['breadcrumbs'][] = Yii::t('app', 'sample');
$this->params['breadcrumbs'][] = ['label' => $model->modelLabel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud person-view">

    <div class="clearfix crud-navigation" style="padding-top: 30px;">
        <div class="pull-left">
            <h1 style="margin-top: 0;">
                <?= $model->modelLabel() ?>
                <small>
                    #<?= $model->id ?>
                    <?php if ($model->is_deleted): ?>
                        <span class="badge">deleted</span>
                    <?php endif; ?>
                </small>
            </h1>
        </div>

        <!-- menu buttons -->
        <div class='pull-right'>
            <div>
                <?=
                SplitDropdown::widget([
                    'model' => $model,
                    'label' => PersonMenu::iconFor('update').'&nbsp; '.BookMenu::labelFor('update'),
                    'encodeLabel' => FALSE,
                    'buttonAction' => 'update',
                    'dropdownActions' => [
                        'view',
                        [
                            'delete',
                            'restore',
                        ],
                    ],
                    'dropdownButtons' => PersonMenu::dropdownButtons(),
                    'urlCreator' => function($action, $model) {
                        return PersonMenu::createUrlFor($action, $model);
                    },
                ]);
                ?>
            </div>
        </div>

    </div>

    <hr/>

    
    <?= 
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
        ],
    ]);
    ?>

    
    <hr/>
    <br/>
    <h3><?= Yii::t('sample', 'Books As Author') ?></h3>
    <div class="table-responsive">
        <?=
        \kartik\grid\GridView::widget([
            'layout' => '{summary}{pager}<br/>{items}{pager}',
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => $model->getBooksAsAuthor(),
                'pagination' => [
                    'pageSize' => 20,
                    'pageParam' => 'page-requestitems',
                ],
            ]),
            'columns' => [
                [
                    'class' => 'kartik\grid\SerialColumn',
                ],
                'name',
            ],
        ]);
        ?>
    </div>
    <br/>
    <h3><?= Yii::t('sample', 'Books As Editor') ?></h3>
    <div class="table-responsive">
        <?=
        \kartik\grid\GridView::widget([
            'layout' => '{summary}{pager}<br/>{items}{pager}',
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => $model->getBooksAsEditor(),
                'pagination' => [
                    'pageSize' => 20,
                    'pageParam' => 'page-requestitems',
                ],
            ]),
            'columns' => [
                [
                    'class' => 'kartik\grid\SerialColumn',
                ],
                'name',
            ],
        ]);
        ?>
    </div>
    <br/>
    <hr/>

    <div style="font-size: 75%; font-style: italic;">
        <?= Yii::t('timestamp', 'Created') ?>
        <?= Yii::$app->formatter->asDate($model->created_at, "eeee, d MMMM Y '".Yii::t('timestamp', 'at')."' HH:mm") ?>
        <?= Yii::t('timestamp', 'by') ?>
        <?= ArrayHelper::getValue($model, 'createdBy.username', '-') ?>
        <br/>
        <?= Yii::t('timestamp', 'Updated') ?>
        <?= Yii::$app->formatter->asDate($model->updated_at, "eeee, d MMMM Y '".Yii::t('timestamp', 'at')."' HH:mm") ?>
        <?= Yii::t('timestamp', 'by') ?>
        <?= ArrayHelper::getValue($model, 'updatedBy.username', '-') ?>
        <?php if ($model->is_deleted): ?>
            <br/>
            <?= Yii::t('timestamp', 'Deleted') ?>
            <?= Yii::$app->formatter->asDate($model->deleted_at, "eeee, d MMMM Y '".Yii::t('timestamp', 'at')."' HH:mm") ?>
            <?= Yii::t('timestamp', 'by') ?>
            <?= ArrayHelper::getValue($model, 'deletedBy.username', '-') ?>
        <?php endif; ?>
    </div>

</div>
