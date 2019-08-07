<?php

use yii\helpers\ArrayHelper;
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

$hasMany = ArrayHelper::getValue($giiConfigs, $tableSchema->fullName.'.hasMany');

echo "<?php\n";
?>

namespace <?= $apiNameSpace ?>;

/**
 * This is the class for REST controller "<?= $controllerClassName ?>".
 */
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\lib\ActionControl;

class <?= $controllerClassName ?> extends \yii\rest\ActiveController
{
    public $modelClass = '<?= $generator->modelClass ?>';
<?php if ($generator->accessFilter): ?>

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
                parent::behaviors(),
                [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'matchCallback' => function ($rule, $action) {return \Yii::$app->user->can($this->module->id . '_' . $this->id . '_' . $action->id, ['route' => true]);},
                        ],
                    ],
                ],
                ]
        );
    }
<?php endif; ?>
<?php if ($hasMany): ?>

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return ArrayHelper::merge(
                parent::actions(),
                [
                'select2-options' => [
                    'class' => \app\lib\Select2Options::class,
                    'modelClass' => $this->modelClass,
                    'text_field' => '<?= $generator->getNameAttribute() ?>',
                ],
                ]
        );
    }
<?php endif; ?>

    /**
     * {@inheritdoc}
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        $config = [
            'class' => $this->actionControls($action),
            'model' => $model,
            'params' => $params,
        ];

        ActionControl::check($config, TRUE);
    }

    /**
     * get access control config for all or spesific action
     * 
     * @param string $action
     * @return array|string
     */
    public function actionControls($action = null)
    {
        $available = [
            'index' => \<?= $actionParentNameSpace ?>\index\ActionControl::class,
            'view' => \<?= $actionParentNameSpace ?>\view\ActionControl::class,
            'create' => \<?= $actionParentNameSpace ?>\create\ActionControl::class,
            'update' => \<?= $actionParentNameSpace ?>\update\ActionControl::class,
            'delete' => \<?= $actionParentNameSpace ?>\delete\ActionControl::class,
<?php if ($softdelete): ?>
            'restore' => \<?= $actionParentNameSpace ?>\restore\ActionControl::class,
            'deleted' => \<?= $actionParentNameSpace ?>\deleted\ActionControl::class,
            'archive' => \<?= $actionParentNameSpace ?>\archive\ActionControl::class,
<?php endif; ?>
        ];

        return $action ? ArrayHelper::getValue($available, $action) : $available;
    }

}