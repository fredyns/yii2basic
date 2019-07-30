<?php
$tableSchema = $generator->getTableSchema();
$softdelete = ($tableSchema->getColumn('is_deleted') !== null);

/**
 * Customizable controller class.
 */
echo "<?php\n";
?>

namespace <?= $controllerNameSpace ?>;

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
                //  uncomment thesse for custom action & access control
                //  'class' => \<?= $actionParentNameSpace ?>\index\ActiveAction::class,
                //  'actionControl' => \<?= $actionParentNameSpace ?>\index\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\IndexAction::class,
                'searchClass' => [
                    'class' => \<?= ltrim($generator->searchModelClass, '\\') ?>::class,
                    'is_deleted' => 0,
                ],
            ],
            'view' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \<?= $actionParentNameSpace ?>\view\ActiveAction::class,
                //  'actionControl' => \<?= $actionParentNameSpace ?>\view\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\ViewAction::class,
                'modelClass' => \<?= ltrim($generator->modelClass, '\\') ?>::class,
            ],
            'create' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \<?= $actionParentNameSpace ?>\create\ActiveAction::class,
                //  'actionControl' => \<?= $actionParentNameSpace ?>\create\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\CreateAction::class,
                'modelClass' => \<?= ltrim($generator->modelClass, '\\') ?>::class,
            ],
            'update' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \<?= $actionParentNameSpace ?>\update\ActiveAction::class,
                //  'actionControl' => \<?= $actionParentNameSpace ?>\update\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\UpdateAction::class,
                'modelClass' => \<?= ltrim($generator->modelClass, '\\') ?>::class,
            ],
            'delete' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \<?= $actionParentNameSpace ?>\delete\ActiveAction::class,
                //  'actionControl' => \<?= $actionParentNameSpace ?>\delete\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\DeleteAction::class,
                'modelClass' => \<?= ltrim($generator->modelClass, '\\') ?>::class,
            ],
<?php if ($softdelete): ?>
            'restore' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \<?= $actionParentNameSpace ?>\restore\ActiveAction::class,
                //  'actionControl' => \<?= $actionParentNameSpace ?>\restore\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\RestoreAction::class,
                'modelClass' => \<?= ltrim($generator->modelClass, '\\') ?>::class,
            ],
            'deleted' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \<?= $actionParentNameSpace ?>\deleted\ActiveAction::class,
                //  'actionControl' => \<?= $actionParentNameSpace ?>\deleted\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\IndexAction::class,
                'searchClass' => [
                    'class' => \<?= ltrim($generator->searchModelClass, '\\') ?>::class,
                    'is_deleted' => 1,
                ],
                'view' => 'deleted',
            ],
            'archive' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \<?= $actionParentNameSpace ?>\archive\ActiveAction::class,
                //  'actionControl' => \<?= $actionParentNameSpace ?>\archive\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\IndexAction::class,
                'searchClass' => [
                    'class' => \<?= ltrim($generator->searchModelClass, '\\') ?>::class,
                ],
                'view' => 'archive',
            ],
<?php endif; ?>
        ];
    }

}
