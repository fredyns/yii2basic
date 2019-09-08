<?php

namespace app\controllers\api\sample;

/**
 * This is the class for REST controller "PersonController".
 */
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\components\ActionControl;

class PersonController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\sample\Person';

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
            'index' => \app\actions\sample\person\index\ActionControl::class,
            'view' => \app\actions\sample\person\view\ActionControl::class,
            'create' => \app\actions\sample\person\create\ActionControl::class,
            'update' => \app\actions\sample\person\update\ActionControl::class,
            'delete' => \app\actions\sample\person\delete\ActionControl::class,
            'restore' => \app\actions\sample\person\restore\ActionControl::class,
            'deleted' => \app\actions\sample\person\deleted\ActionControl::class,
            'archive' => \app\actions\sample\person\archive\ActionControl::class,
        ];

        return $action ? ArrayHelper::getValue($available, $action) : $available;
    }

}