<?php

namespace app\actions\sample\book\index;

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
        return $this->passed();
    }

}