<?php
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

$modelMeta = \app\generators\giiconfig\Generator::readMetadata();
$tableSchema = $generator->getTableSchema();
$softdelete = ($tableSchema->getColumn('is_deleted') !== null);
$hasMany = ArrayHelper::getValue($modelMeta, $tableSchema->fullName.'.hasMany');
/**
 * Customizable controller class.
 */
echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($restClass, '\\')) ?>;

/**
 * This is the class for REST controller "<?= $controllerClassName ?>".
 */
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\lib\AccessControl as ActionControl;

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
            'class' => $this->accessControls($action),
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
    public function accessControls($action = null)
    {
        $available = [
            'index' => \<?= $actionParentNameSpace ?>\index\AccessControl::class,
            'view' => \<?= $actionParentNameSpace ?>\view\AccessControl::class,
            'create' => \<?= $actionParentNameSpace ?>\create\AccessControl::class,
            'update' => \<?= $actionParentNameSpace ?>\update\AccessControl::class,
            'delete' => \<?= $actionParentNameSpace ?>\delete\AccessControl::class,
<?php if ($softdelete): ?>
            'restore' => \<?= $actionParentNameSpace ?>\restore\AccessControl::class,
            'deleted' => \<?= $actionParentNameSpace ?>\deleted\AccessControl::class,
            'archive' => \<?= $actionParentNameSpace ?>\archive\AccessControl::class,
<?php endif; ?>
        ];

        return $action ? ArrayHelper::getValue($available, $action) : $available;
    }

}