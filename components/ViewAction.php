<?php

namespace app\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;

/**
 * Description of ActiveAction
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class ViewAction extends \app\base\BaseAction
{
    public $modelClass;

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
         * running action actionControl to check whether user has priviledges to run action
         */
        $passed = $this->actionControlFilter($model);

        if ($passed === FALSE) {
            return $this->fallbackPage($model);
        }

        return $this->controller->render('view', [
                'model' => $model,
        ]);
    }

}