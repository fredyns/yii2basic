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
    public $redirect;

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

        try {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $url = $this->resolveRedirect($model);
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
     * resolve url to redirect
     * 
     * @param \yii\db\ActiveRecord $model
     * @return array
     */
    public function resolveRedirect($model)
    {
        if ($this->redirect && is_array($this->redirect)) {
            return $this->redirect;
        }

        if (is_callable($this->redirect)) {
            return call_user_func($this->redirect, $model);
        }

        return ['view', 'id' => $model->id];
    }

}