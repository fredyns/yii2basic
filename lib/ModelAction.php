<?php

namespace app\lib;

use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\UserException;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Generic action for model operation
 * this action would require:
 *  - model 'id' as parameter
 *  - model function name to execute (optional)
 *  - 'redirect' url after execution success (optional)
 *  - 'fallback' url after execution failed/error (optional)
 *  - 'view' to display 
 *
 * @property Filter $filter action filter
 * 
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class ModelAction extends BaseAction
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
     * @var AccessControl action access control before execution
     */
    public $filter;

    /**
     * @var String|Callable then function name on model or inline function to execute
     */
    public $operation;

    /**
     * @var array redirect url after operation succeed
     */
    public $redirect;

    /**
     * @var array redirect url when error occur while executing operation
     */
    public $fallback;

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

        if (is_array($this->filter)) {
            $this->filter = Yii::createObject($this->filter);

            if (($this->filter instanceof AccessControl) === FALSE) {
                throw new InvalidConfigException('Filter must extend from '.AccessControl::class.'.');
            }
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
         * running action filter to check whether user has priviledges to run action
         */
        if ($this->filter && $this->filter instanceof AccessControl) {
            $permitted = $this->filter->run();

            if ($permitted === FALSE) {
                if ($this->fallback) {
                    foreach ($this->filter->messages as $msg) {
                        Yii::$app->getSession()->addFlash('error', $msg);
                    }

                    $url = $this->resolveFallback($model);
                    return $this->controller->redirect($url);
                } elseif (count($this->filter->messages) > 0) {
                    $msg = implode("\n", $this->filter->messages);
                    throw new NotFoundHttpException($msg);
                } else {
                    throw new NotFoundHttpException(Yii::t('app', 'Operation is forbidden for unknown reason.'));
                }
            }
        }

        /**
         * try to perform model operation (if any)
         * and handle operation result
         */
        try {
            $result = $this->startOperation($model);

            if ($result && $this->redirect) {
                $url = $this->resolveRedirect($model);
                return $this->controller->redirect($url);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();

            if (empty($this->fallback)) {
                $model->addError('_exception', $msg);
            } else {
                Yii::$app->getSession()->addFlash('error', $msg);

                $url = $this->resolveFallback($model);
                return $this->controller->redirect($url);
            }
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

        $function_exist = (is_scalar($this->operation) && method_exists($this->modelClass, $this->operation));
        $is_closure = ($this->operation instanceof \Closure);

        if ($function_exist) {
            Yii::debug('Running operation: '.get_class($model).'::'.$this->operation.'()', __METHOD__);

            return call_user_func([$model, $this->operation], $model);
        } elseif ($is_closure) {
            Yii::debug('Running closure', __METHOD__);

            return call_user_func($this->operation, $model);
        } else {
            throw new InvalidConfigException("Operation is not executable.");
        }
    }

    /**
     * resolve url path
     * 
     * @param \yii\db\ActiveRecord $model
     * @return array
     */
    protected function resolveUrl($url, $model)
    {
        if ($url && is_array($url)) {
            return $url;
        }

        if (is_callable($url)) {
            return call_user_func($url, $model);
        }

        return Url::previous();
    }

    /**
     * resolve url to redirect when deletion successfull
     * 
     * @param \yii\db\ActiveRecord $model
     * @return array
     */
    protected function resolveRedirect($model)
    {
        return $this->resolveUrl($this->redirect, $model);
    }

    /**
     * resolve url to fallback when deletion failed
     * 
     * @param \yii\db\ActiveRecord $model
     * @return array
     */
    protected function resolveFallback($model)
    {
        return $this->resolveUrl($this->fallback, $model);
    }

}