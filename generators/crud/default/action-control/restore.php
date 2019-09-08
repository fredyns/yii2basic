<?php
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

echo "<?php\n";
?>

namespace <?= $actionNameSpace ?>;

use Yii;
use yii\db\ActiveRecord;

/**
 * Action Access control checks all relevan condition to decide whether an action is executable
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class ActionControl extends \app\components\ActionControl
{

    /**
     * @inheritdoc
     */
    public function run()
    {
        /**
         * user context
         */
        if (Yii::$app->user->isGuest) {
            return $this->blocked(Yii::t("action-control", "You have to login first."));
        }

        if (Yii::$app->user->identity->isAdmin == FALSE) {
            return $this->blocked(Yii::t("action-control", "You are not authorized to access this page."));
        }

        /**
         * model context
         */
        if (empty($this->model)) {
            return $this->blocked(Yii::t("action-control", "Data not found."));
        }

        if (($this->model instanceof ActiveRecord) == FALSE) {
            return $this->blocked(Yii::t("action-control", "Model error."));
        }

        if ($this->model->hasAttribute('is_deleted') == FALSE) {
            return $this->blocked(Yii::t("action-control", "Data doesn't support softdelete."));
        }

        /**
         * data integrity
         */
        if ($this->model->getAttribute('is_deleted') == FALSE) {
            return $this->blocked(Yii::t("action-control", "Can't restore undeleted data."));
        }

        /**
         * check passed
         */
        return $this->passed();
    }

}