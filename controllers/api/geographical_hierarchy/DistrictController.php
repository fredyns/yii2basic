<?php

namespace app\controllers\api\geographical_hierarchy;

/**
 * This is the class for REST controller "DistrictController".
 */
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\components\ActionControl;

class DistrictController extends \yii\rest\ActiveController
{
    public $modelClass = \app\models\geographical_hierarchy\District::class;

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
            'index' => \app\actions\geographical_hierarchy\district\index\ActionControl::class,
            'view' => \app\actions\geographical_hierarchy\district\view\ActionControl::class,
            'create' => \app\actions\geographical_hierarchy\district\create\ActionControl::class,
            'update' => \app\actions\geographical_hierarchy\district\update\ActionControl::class,
            'delete' => \app\actions\geographical_hierarchy\district\delete\ActionControl::class,
        ];

        return $action ? ArrayHelper::getValue($available, $action) : $available;
    }

}