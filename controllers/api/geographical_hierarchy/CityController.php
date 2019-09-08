<?php

namespace app\controllers\api\geographical_hierarchy;

/**
 * This is the class for REST controller "CityController".
 */
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\components\ActionControl;

class CityController extends \yii\rest\ActiveController
{
    public $modelClass = \app\models\geographical_hierarchy\City::class;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return ArrayHelper::merge(
                parent::actions(),
                [
                'select2-options' => [
                    'class' => \app\components\Select2Options::class,
                    'modelClass' => $this->modelClass,
                    'text_field' => 'name',
                ],
                ]
        );
    }

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
            'index' => \app\actions\geographical_hierarchy\city\index\ActionControl::class,
            'view' => \app\actions\geographical_hierarchy\city\view\ActionControl::class,
            'create' => \app\actions\geographical_hierarchy\city\create\ActionControl::class,
            'update' => \app\actions\geographical_hierarchy\city\update\ActionControl::class,
            'delete' => \app\actions\geographical_hierarchy\city\delete\ActionControl::class,
        ];

        return $action ? ArrayHelper::getValue($available, $action) : $available;
    }

}