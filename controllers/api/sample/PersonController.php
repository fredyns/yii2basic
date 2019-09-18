<?php

namespace app\controllers\api\sample;

/**
 * This is the class for REST controller "PersonController".
 */
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\lib\sample\person\PersonAC;

class PersonController extends \yii\rest\ActiveController
{
    public $modelClass = \app\models\sample\Person::class;

    /**
     * {@inheritdoc}
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        PersonAC::catchError();

        $allow = PersonAC::can($action);

        if (!$allow) {
            throw PersonAC::exception();
        }
    }

}