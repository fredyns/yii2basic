<?php

use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/* @var $this \yii\web\View  */
/* @var $generator \app\generators\crud\Generator  */
/* @var $tableSchema \yii\db\TableSchema  */
/* @var $softdelete bool  */
/* @var $modelClassName string  */
/* @var $modelSlug string  */
/* @var $modelName string  */
/* @var $model \yii\db\ActiveRecord  */
/* @var $searchClassName string search model class name w/o namespace */
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

$hasManyRelations = $generator->getModelRelations($generator->modelClass, ['has_many', 'many_many']);
$hasMany = (count($hasManyRelations) > 0);

echo "<?php\n";
?>

namespace <?= $apiNameSpace ?>;

/**
 * This is the class for REST controller "<?= $controllerClassName ?>".
 */
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use <?= $acNameSpace ?>\<?= $acClassName ?>;

class <?= $controllerClassName ?> extends \yii\rest\ActiveController
{
    public $modelClass = \<?= $generator->modelClass ?>::class;
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
                    'class' => \app\components\Select2Options::class,
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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        <?= $acClassName ?>::catchError();

        $allow = <?= $acClassName ?>::can($action);

        if (!$allow) {
            throw <?= $acClassName ?>::exception();
        }
    }

}