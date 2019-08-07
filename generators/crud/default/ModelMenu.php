<?php
use yii\db\Schema;
use yii\helpers\StringHelper;
use yii\helpers\Inflector;

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

$modelClassName = StringHelper::basename($generator->modelClass);
$route = ($subPath ? "/{$subPath}/" : "/")
    .Inflector::camel2id(str_replace('Controller', '', $controllerClassName));

echo "<?php\n";
?>

namespace <?= StringHelper::dirname($generator->searchModelClass) ?>;

/**
 * Action menu manager for model <?= $modelClassName ?>
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class <?= $modelClassName ?>Menu extends \app\lib\ModelMenu
{
    public static $controller = '<?= $route ?>';
    public static $softdelete = <?= $tableSchema->getColumn('is_deleted') !== NUll ? 'true' : 'false' ?>;

    public static function actionControls()
    {
        $classes = [
            static::INDEX => \<?= $actionParentNameSpace ?>\index\ActionControl::class,
            static::VIEW => \<?= $actionParentNameSpace ?>\view\ActionControl::class,
            static::CREATE => \<?= $actionParentNameSpace ?>\create\ActionControl::class,
            static::UPDATE => \<?= $actionParentNameSpace ?>\update\ActionControl::class,
            static::DELETE => \<?= $actionParentNameSpace ?>\delete\ActionControl::class,
<?php if ($tableSchema->getColumn('is_deleted') !== NUll): ?>
            static::DELETED => \<?= $actionParentNameSpace ?>\deleted\ActionControl::class,
            static::RESTORE => \<?= $actionParentNameSpace ?>\restore\ActionControl::class,
            static::ARCHIVE => \<?= $actionParentNameSpace ?>\archive\ActionControl::class,
<?php endif; ?>
        ];
        // remove this line to disable default action controls
        $classes = parent::actionControls();

        return $classes;
    }

}