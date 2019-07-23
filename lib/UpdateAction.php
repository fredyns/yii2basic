<?php

namespace app\lib;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * Description of ActiveAction
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class UpdateAction extends BaseAction
{
    public $modelClass;
    public $redirectUrl;

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
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $url = $this->resolveRedirectUrl($model);
                return $this->controller->redirect($url);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        return $this->controller->render('update', [
                'model' => $model,
        ]);
    }

    /**
     * resolve url to redirect when creation successfull
     *
     * @param \yii\db\ActiveRecord $model
     * @return array
     */
    protected function resolveRedirectUrl($model)
    {
        if (empty($this->redirectUrl)) {
            return ['view', 'id' => $model->id];
        }

        return $this->resolveUrl($this->redirectUrl, $model);
    }

}