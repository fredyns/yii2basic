<?php

namespace app\actions\sample\person\archive;

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
         * check passed
         */
        return $this->passed();
    }

}