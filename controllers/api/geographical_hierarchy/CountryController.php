<?php

namespace app\controllers\api\geographical_hierarchy;

/**
 * This is the class for REST controller "CountryController".
 */
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\lib\ActionControl;

class CountryController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\geographical_hierarchy\Country';

    /**
     * {@inheritdoc}
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        $config = [
            'class' => $this->actionControls($action),
            'model' => $model,
            'params' => $params,
        ];

        ActionControl::check($config, TRUE);
    }

    /**
     * get access control config for all or spesific action
     * 
     * @param string $action
     * @return array|string
     */
    public function actionControls($action = null)
    {
        $available = [
            'index' => \app\actions\geographical_hierarchy\country\index\ActionControl::class,
            'view' => \app\actions\geographical_hierarchy\country\view\ActionControl::class,
            'create' => \app\actions\geographical_hierarchy\country\create\ActionControl::class,
            'update' => \app\actions\geographical_hierarchy\country\update\ActionControl::class,
            'delete' => \app\actions\geographical_hierarchy\country\delete\ActionControl::class,
        ];

        return $action ? ArrayHelper::getValue($available, $action) : $available;
    }

}