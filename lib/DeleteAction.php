<?php

namespace app\lib;

use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Description of ActiveAction
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class DeleteAction extends BaseAction
{
    public $modelClass;
    public $redirect;
    public $fallback;

    /**
     * execute action
     * @return mixed
     */
    public function run($id)
    {
        $model = $this->modelClass::findOne($id);

        if (empty($model)) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        /**
         * running action accessControl to check whether user has priviledges to run action
         */
        $passed = $this->accessControlFilter($model);

        if ($passed === FALSE) {
            return $this->fallbackPage($model);
        }

        try {
            $model->delete();
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            Yii::$app->getSession()->addFlash('error', $msg);

            $url = $this->resolveFallback($model);
            return $this->controller->redirect($url);
        }

        $url = $this->resolveRedirect($model);
        return $this->controller->redirect($url);
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