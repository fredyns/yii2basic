<?php

namespace app\lib;

use Yii;
use yii\base\InvalidConfigException;

/**
 * generic action to create new model
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class CreateAction extends BaseAction
{
    public $modelClass;
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
    public function run()
    {
        /**
         * running action actionControl to check whether user has priviledges to run action
         */
        $passed = $this->actionControlFilter();

        if ($passed === FALSE) {
            return $this->fallbackPage();
        }

        $model = Yii::createObject($this->modelClass);

        try {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $url = $this->resolveRedirectUrl($model);
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