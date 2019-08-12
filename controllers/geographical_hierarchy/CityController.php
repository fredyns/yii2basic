<?php

namespace app\controllers\geographical_hierarchy;

use Yii;
use yii\web\Controller;

/**
 * This is the class for controller "CityController".
 */
class CityController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\city\index\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\city\index\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\IndexAction::class,
                'searchClass' => [
                    'class' => \app\actions\geographical_hierarchy\city\CitySearch::class,
                ],
            ],
            'view' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\city\view\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\city\view\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\ViewAction::class,
                'modelClass' => \app\models\geographical_hierarchy\City::class,
            ],
            'create' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\city\create\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\city\create\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\CreateAction::class,
                'modelClass' => \app\models\geographical_hierarchy\City::class,
            ],
            'update' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\city\update\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\city\update\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\UpdateAction::class,
                'modelClass' => \app\models\geographical_hierarchy\City::class,
            ],
            'delete' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\city\delete\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\city\delete\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\DeleteAction::class,
                'modelClass' => \app\models\geographical_hierarchy\City::class,
            ],
        ];
    }

}
