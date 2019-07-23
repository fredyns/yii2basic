<?php

namespace app\lib;

use yii\db\ActiveRecord;

/**
 * base class to check whether an action is executable
 * regarding user log in status & all situation
 * 
 * How to use:
 *  $control = new AccessControl($model);
 * 
 *  if ($control->isPassed) {
 *      // run action
 *  }
 * 
 * Or:
 *  if (AccessControl::check($model)) {
 *      // run action
 *  }
 * 
 * inherited access control must overide run() function to define assessment
 * 
 * @property ActiveRecord $model particular model to check
 * @property Boolean $isPassed whether user passed access control to run action
 * @property String[] $messages error messages produced on checking process
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class AccessControl extends \yii\base\Component
{
    public $model;
    public $messages = [];

    /**
     * run access control and return pass or not
     * @param ActiveRecord $model
     * @return Boolean
     */
    public static function check($model)
    {
        $access_control = new static($model);

        $access_control->run();

        return $access_control->isPassed;
    }

    /**
     * @inheritdoc
     */
    public function __construct($config_or_model = array())
    {
        $config = ($config_or_model instanceof ActiveRecord) ? ['model' => $config_or_model] : $config;

        return parent::__construct($config);
    }
    /**
     * @var Boolean
     */
    private $_isPassed;

    /**
     * chcek whether user pass access control to run action
     * @param Boolean $force
     * @return Boolean
     */
    public function getIsPassed($force = false)
    {
        if ($this->_isPassed !== NULL && $force === FALSE) {
            return $this->_isPassed;
        } elseif ($force) {
            $this->resetState();
        }

        return $this->run();
    }

    /**
     * all logic to check whether action is executable
     * @return boolean
     */
    public function run()
    {
        return $this->passed();
    }

    /**
     * reset checking state
     */
    protected function resetState()
    {
        $this->_isPassed = NULL;
        $this->messages = [];
    }

    /**
     * set access control to be true
     * @return Boolean
     */
    public function passed()
    {
        return $this->_isPassed = TRUE;
    }

    /**
     * set access control to be false
     * and save message
     * @return Boolean
     */
    public function blocked($message)
    {
        $this->messages[] = $message;

        return $this->_isPassed = FALSE;
    }

}