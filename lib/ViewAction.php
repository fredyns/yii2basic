<?php

namespace app\lib;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * Description of ActiveAction
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class ViewAction extends BaseAction
{
    public $modelClass;

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

        return $this->controller->render('view', [
                'model' => $model,
        ]);
    }

}