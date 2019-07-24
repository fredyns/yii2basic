<?php
$tableSchema = $generator->getTableSchema();
$softdelete = ($tableSchema->getColumn('is_deleted') !== null);

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
                'searchClass' => [
                    'class' => \<?= ltrim($generator->searchModelClass, '\\') ?>::class,
                    'is_deleted' => 0,
                ],
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
<?php if ($softdelete): ?>
            'restore' => [
                'class' => \app\lib\RestoreAction::class,
                'modelClass' => \<?= ltrim($generator->modelClass, '\\') ?>::class,
            ],
            'deleted' => [
                'class' => \app\lib\IndexAction::class,
                'searchClass' => [
                    'class' => \<?= ltrim($generator->searchModelClass, '\\') ?>::class,
                    'is_deleted' => 1,
                ],
                'view' => 'deleted',
            ],
            'archive' => [
                'class' => \app\lib\IndexAction::class,
                'searchClass' => [
                    'class' => \<?= ltrim($generator->searchModelClass, '\\') ?>::class,
                ],
                'view' => 'deleted',
            ],
<?php endif; ?>
        ];
    }

}