<?php

namespace app\controllers\api\geographical_hierarchy;

/**
 * This is the class for REST controller "RegionController".
 */
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\lib\ActionControl;

class RegionController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\geographical_hierarchy\Region';

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
            'index' => \app\actions\geographical_hierarchy\region\index\ActionControl::class,
            'view' => \app\actions\geographical_hierarchy\region\view\ActionControl::class,
            'create' => \app\actions\geographical_hierarchy\region\create\ActionControl::class,
            'update' => \app\actions\geographical_hierarchy\region\update\ActionControl::class,
            'delete' => \app\actions\geographical_hierarchy\region\delete\ActionControl::class,
        ];

        return $action ? ArrayHelper::getValue($available, $action) : $available;
    }

}