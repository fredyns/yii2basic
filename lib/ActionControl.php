<?php

namespace app\lib;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\ForbiddenHttpException;

/**
 * base class to check whether an action is executable
 * regarding user log in status & all situation
 * 
 * How to use:
 *  $control = new ActionControl($model);
 * 
 *  if ($control->isPassed) {
 *      // run action
 *  }
 * 
 * Or:
 *  if (ActionControl::check($model)) {
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
class ActionControl extends \yii\base\Component
{
    public $model;
    public $messages = [];
    public $params = [];

    /**
     * build access control instance
     * @param array $config
     * @return static
     * @throws InvalidConfigException
     */
    public static function build($config)
    {
        /* @var $access_control ActionControl */
        $access_control = Yii::createObject($config);
        $class = self::class;

        if (($access_control instanceof $class) == FALSE) {
            throw new InvalidConfigException("Access control must extend from {$class}.");
        }

        return $access_control;
    }

    /**
     * run access control check
     * 
     * @param array $config
     * @throws InvalidConfigException
     * @throws ForbiddenHttpException
     */
    public static function check($config, $throw = false)
    {
        /* @var $access_control ActionControl */
        $access_control = static::build($config);

        if (!$access_control->isPassed && $throw) {
            throw $access_control->exception();
        }

        return $access_control->isPassed;
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
        if ($this->_isPassed === NULL OR $force) {
            $this->resetState();
            $this->run();
        }

        $result = $this->_isPassed ? 'passed' : 'blocked';
        $log = get_called_class()
            .'('.$this->model::tableName().'#'.$this->model->id.'): '.$result
            ."\n"
            .'- '.implode("\n- ",$this->messages);
        Yii::debug($log);

        return $this->_isPassed;
    }

    /**
     * all assessment logic to check whether user granted to run action
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

    /**
     * put messages to session
     */
    public function setSessionMessages($category = 'error')
    {
        foreach ($this->messages as $msg) {
            Yii::$app->getSession()->addFlash($category, $msg);
        }
    }

    /**
     * throw messages as exception
     * @return ForbiddenHttpException
     */
    public function exception()
    {
        if (count($this->messages) > 0) {
            $msg = implode("\n", $this->messages);
        } else {
            $msg = Yii::t('app', 'Action is forbidden for unknown reason.');
        }

        return new ForbiddenHttpException($msg);
    }

}