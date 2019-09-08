<?php

namespace app\components;

use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\UserException;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use cornernote\returnurl\ReturnUrl;

/**
 * Generic action for model operation
 * this action would require:
 *  - model 'id' as parameter
 *  - model function name to execute (optional)
 *  - 'redirect' url after execution success (optional)
 *  - 'fallback' url if user has no access or error occured (optional)
 *  - 'view' to display 
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class ModelAction extends \app\base\BaseAction
{
    /**
     * @var string primary key attribute name of model
     */
    public $pk = 'id';

    /**
     * @var String define model class name
     */
    public $modelClass;

    /**
     * @var string scenario of model operation
     */
    public $scenario;

    /**
     * @var String|Callable then function name on model or inline function to execute
     */
    public $operation;

    /**
     * @var array redirect url when error occur while executing operation
     */
    public $errorUrl;

    /**
     * @var array redirect url after operation succeed
     */
    public $redirectUrl;

    /**
     * @var string view to be rendered
     */
    public $view = 'view';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (empty($this->modelClass)) {
            throw new InvalidConfigException('Model class must be defined.');
        }

        if ($this->operation) {
            $function_exist = (is_scalar($this->operation) && method_exists($this->modelClass, $this->operation));
            $is_closure = ($this->operation instanceof \Closure);
            if ($function_exist === FALSE && $is_closure === FALSE) {
                throw new InvalidConfigException("Operation is not executable.");
            }
        }
    }

    /**
     * 
     * @param int $id
     * @return mixed
     */
    public function run()
    {
        /**
         * open particullar model
         */
        /* @var $model ActiveRecord */
        $model = $this->resolveModel();

        /**
         * running action actionControl to check whether user has priviledges to run action
         */
        $passed = $this->actionControlFilter($model);

        if ($passed === FALSE) {
            return $this->fallbackPage($model);
        }

        /**
         * try to perform model operation (if any)
         * and handle operation result
         */
        try {
            $result = $this->startOperation($model);

            if ($result && $this->redirectUrl) {
                $url = $this->resolveRedirectUrl($model);
                return $this->controller->redirect($url);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $url = $this->resolveErrorUrl($model);

            if ($url) {
                Yii::$app->getSession()->addFlash('error', $msg);
                return $this->controller->redirect($url);
            }

            $model->addError('_exception', $msg);
        }

        /**
         * diplay a view
         */
        return $this->controller->render($this->view, ['model' => $model]);
    }

    /**
     * Finds the model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function resolveModel()
    {
        $key = Yii::$app->request->getQueryParam($this->pk);

        if (empty($key)) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        if (($model = $this->modelClass::findOne([$this->pk => $key])) === null) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        if ($this->scenario) {
            $model->setScenario($this->scenario);
        }

        return $model;
    }

    /**
     * executing model operation
     * 
     * @param type $model
     * @return type
     */
    protected function startOperation($model)
    {
        if (empty($this->operation)) {
            return;
        }

        $is_function = (is_string($this->operation) && method_exists($this->modelClass, $this->operation));
        $is_closure = ($this->operation instanceof \Closure);

        if ($is_function) {
            Yii::debug('Running operation: '.get_class($model).'::'.$this->operation.'()', __METHOD__);

            return call_user_func([$model, $this->operation]);
        } elseif ($is_closure) {
            Yii::debug('Running operation closure', __METHOD__);

            return call_user_func($this->operation, $model);
        } else {
            throw new InvalidConfigException("Operation is not executable.");
        }
    }

    /**
     * resolve url to fallback when deletion failed
     * 
     * @param \yii\db\ActiveRecord $model
     * @return array
     */
    protected function resolveErrorUrl($model)
    {
        if (empty($this->errorUrl)) {
            return NULL;
        }

        return $this->resolveUrl($this->errorUrl, $model);
    }

    /**
     * resolve url to redirect when deletion successfull
     * 
     * @param \yii\db\ActiveRecord $model
     * @return array
     */
    protected function resolveRedirectUrl($model)
    {
        return $this->resolveUrl($this->redirectUrl, $model);
    }

}