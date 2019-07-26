<?php
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

$modelMeta = \app\generators\modelmeta\Generator::readMetadata();
$tableSchema = $generator->getTableSchema();
$hasMany = ArrayHelper::getValue($modelMeta, $tableSchema->fullName.'hasMany');
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

}