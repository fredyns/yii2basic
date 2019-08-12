<?php

namespace app\controllers\api\geographical_hierarchy;

/**
 * This is the class for REST controller "TypeController".
 */
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\lib\ActionControl;

class TypeController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\geographical_hierarchy\Type';

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
            'index' => \app\actions\geographical_hierarchy\type\index\ActionControl::class,
            'view' => \app\actions\geographical_hierarchy\type\view\ActionControl::class,
            'create' => \app\actions\geographical_hierarchy\type\create\ActionControl::class,
            'update' => \app\actions\geographical_hierarchy\type\update\ActionControl::class,
            'delete' => \app\actions\geographical_hierarchy\type\delete\ActionControl::class,
        ];

        return $action ? ArrayHelper::getValue($available, $action) : $available;
    }

}