<?php

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

echo "<?php\n";
?>

namespace <?= StringHelper::dirname($generator->searchModelClass) ?>;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * control which action is executable by user
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class <?= $modelClassName ?>AC extends \app\components\ActionControl
{

    public static function canIndex()
    {
        return true;
    }

    public static function canView()
    {
        return true;
    }

    public static function canCreate()
    {
        return true;
    }

    public static function canUpdate()
    {
        return true;
    }

    public static function canDelete()
    {
        return static::isLoggedIn();
    }
<?php if ($softdelete): ?>

    public static function canRestore()
    {
        return static::isAdmin();
    }

    public static function canListArchive()
    {
        return static::isAdmin();
    }

    public static function canListDeleted()
    {
        return static::isAdmin();
    }
<?php endif; ?>

}