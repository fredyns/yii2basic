<?php

namespace app\controllers\api\sample;

/**
 * This is the class for REST controller "BookController".
 */
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\lib\ActionControl;

class BookController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\sample\Book';

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
            'index' => \app\actions\sample\book\index\ActionControl::class,
            'view' => \app\actions\sample\book\view\ActionControl::class,
            'create' => \app\actions\sample\book\create\ActionControl::class,
            'update' => \app\actions\sample\book\update\ActionControl::class,
            'delete' => \app\actions\sample\book\delete\ActionControl::class,
            'restore' => \app\actions\sample\book\restore\ActionControl::class,
            'deleted' => \app\actions\sample\book\deleted\ActionControl::class,
            'archive' => \app\actions\sample\book\archive\ActionControl::class,
        ];

        return $action ? ArrayHelper::getValue($available, $action) : $available;
    }

}