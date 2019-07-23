<?php

namespace app\lib;

use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use cornernote\returnurl\ReturnUrl;

/**
 * Description of ActiveAction
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class DeleteAction extends BaseAction
{
    public $modelClass;
    public $errorUrl;
    public $redirectUrl;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (empty($this->modelClass)) {
            throw new InvalidConfigException('Model class must be defined.');
        }
    }

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

            $url = $this->resolveErrorUrl($model);
            return $this->controller->redirect($url);
        }

        $url = $this->resolveRedirectUrl($model);
        return $this->controller->redirect($url);
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

    /**
     * resolve url to fallback when deletion failed
     *
     * @param \yii\db\ActiveRecord $model
     * @return array
     */
    protected function resolveErrorUrl($model)
    {
        return $this->resolveUrl($this->errorUrl, $model);
    }

}