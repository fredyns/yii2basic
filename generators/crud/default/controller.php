<?php
/**
 * Customizable controller class.
 */
echo "<?php\n";
?>

namespace <?= \yii\helpers\StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use yii\web\Controller;

/**
 * This is the class for controller "<?= $controllerClassName ?>".
 */
class <?= $controllerClassName ?> extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => \app\lib\IndexAction::class,
                'searchClass' => \<?= ltrim($generator->searchModelClass, '\\') ?>::class,
            ],
            'view' => [
                'class' => \app\lib\ViewAction::class,
                'modelClass' => \<?= ltrim($generator->modelClass, '\\') ?>::class,
            ],
            'create' => [
                'class' => \app\lib\CreateAction::class,
                'modelClass' => \<?= ltrim($generator->modelClass, '\\') ?>::class,
            ],
            'update' => [
                'class' => \app\lib\UpdateAction::class,
                'modelClass' => \<?= ltrim($generator->modelClass, '\\') ?>::class,
            ],
            'delete' => [
                'class' => \app\lib\DeleteAction::class,
                'modelClass' => \<?= ltrim($generator->modelClass, '\\') ?>::class,
            ],
        ];
    }

}