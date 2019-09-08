<?php

namespace app\actions\sample\person\restore;

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