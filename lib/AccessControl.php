<?php

namespace app\lib;

use yii\db\ActiveRecord;

/**
 * base class to check whether an action is executable
 * regarding user log in status & all situation
 * 
 * @property ActiveRecord $model particular model
 * @property Boolean $pass whether action is executable
 * @property String[] $messages error messages produced on checking process
 * @property-read array $redirectUrl redirect url when error occur
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class AccessControl extends \yii\base\Component
{
    public $model;
    public $pass = FALSE;
    public $messages = [];

    /**
     * run access control and return pass or not
     * @param ActiveRecord $model
     * @return Boolean
     */
    public static function assess($model)
    {
        $access_control = new static($model);

        $access_control->run();

        return $access_control->pass;
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
     * reset checking state
     */
    protected function resetState()
    {
        $this->pass = NULL;
        $this->messages = [];
    }

    /**
     * compose redirect url when error occur
     * @return array
     */
    public function getRedirectUrl()
    {
        return ['/'];
    }

    /**
     * all logic to check whether action is executable
     * @return boolean
     */
    public function run()
    {
        $this->resetState();

        return $this->passed();
    }

    /**
     * set access control to be true
     * @return Boolean
     */
    public function passed()
    {
        return $this->pass = TRUE;
    }

    /**
     * set access control to be false
     * and save message
     * @return Boolean
     */
    public function blocked($message)
    {
        $this->messages[] = $message;

        return $this->pass = FALSE;
    }

}