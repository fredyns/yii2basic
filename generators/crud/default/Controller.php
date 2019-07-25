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
                'class' => \<?= $actionParentNameSpace ?>\index\ActiveAction::class,
                'searchClass' => [
                    'class' => \<?= ltrim($generator->searchModelClass, '\\') ?>::class,
                    'is_deleted' => 0,
                ],
            ],
            'view' => [
                'class' => \<?= $actionParentNameSpace ?>\view\ActiveAction::class,
                'modelClass' => \<?= ltrim($generator->modelClass, '\\') ?>::class,
            ],
            'create' => [
                'class' => \<?= $actionParentNameSpace ?>\create\ActiveAction::class,
                'modelClass' => \<?= ltrim($generator->modelClass, '\\') ?>::class,
            ],
            'update' => [
                'class' => \<?= $actionParentNameSpace ?>\update\ActiveAction::class,
                'modelClass' => \<?= ltrim($generator->modelClass, '\\') ?>::class,
            ],
            'delete' => [
                'class' => \<?= $actionParentNameSpace ?>\delete\ActiveAction::class,
                'modelClass' => \<?= ltrim($generator->modelClass, '\\') ?>::class,
            ],
<?php if ($softdelete): ?>
            'restore' => [
                'class' => \<?= $actionParentNameSpace ?>\restore\ActiveAction::class,
                'modelClass' => \<?= ltrim($generator->modelClass, '\\') ?>::class,
            ],
            'deleted' => [
                'class' => \<?= $actionParentNameSpace ?>\deleted\ActiveAction::class,
                'searchClass' => [
                    'class' => \<?= ltrim($generator->searchModelClass, '\\') ?>::class,
                    'is_deleted' => 1,
                ],
                'view' => 'deleted',
            ],
            'archive' => [
                'class' => \<?= $actionParentNameSpace ?>\archive\ActiveAction::class,
                'searchClass' => [
                    'class' => \<?= ltrim($generator->searchModelClass, '\\') ?>::class,
                ],
                'view' => 'archive',
            ],
<?php endif; ?>
        ];
    }

}