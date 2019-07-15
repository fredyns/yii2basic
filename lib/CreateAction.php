<?php

namespace app\lib;

use Yii;

/**
 * generic action to create new model
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class CreateAction extends BaseAction
{
    public $modelClass;
    public $redirect;

    /**
     * execute action
     * @return mixed
     */
    public function run()
    {
        $model = Yii::createObject($this->modelClass);

        try {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $url = $this->resolveRedirect($model);
                return $this->controller->redirect($url);
            } elseif (!\Yii::$app->request->isPost) {
                $model->load(Yii::$app->request->get());
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        return $this->controller->render('create', [
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