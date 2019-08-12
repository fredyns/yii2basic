<?php

namespace app\actions\geographical_hierarchy\region\delete;

use Yii;
use yii\db\ActiveRecord;

/**
 * Action Access control checks all relevan condition to decide whether an action is executable
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class ActionControl extends \app\lib\ActionControl
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

        /**
         * model context
         */
        if (empty($this->model)) {
            return $this->blocked(Yii::t("action-control", "Data not found."));
        }

        if (($this->model instanceof ActiveRecord) == FALSE) {
            return $this->blocked(Yii::t("action-control", "Model error."));
        }

        /**
         * data integrity
         */
        if ($this->model->hasAttribute('is_deleted')) {
            if ($this->model->getAttribute('is_deleted')) {
                return $this->blocked(Yii::t("action-control", "Data already deleted."));
            }
        }

        /**
         * check passed
         */
        return $this->passed();
    }

}