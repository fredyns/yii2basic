<?php

namespace app\controllers\geographical_hierarchy;

use Yii;
use yii\web\Controller;

/**
 * This is the class for controller "RegionController".
 */
class RegionController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\region\index\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\region\index\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\IndexAction::class,
                'searchClass' => [
                    'class' => \app\actions\geographical_hierarchy\region\RegionSearch::class,
                ],
            ],
            'view' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\region\view\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\region\view\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\ViewAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Region::class,
            ],
            'create' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\region\create\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\region\create\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\CreateAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Region::class,
            ],
            'update' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\region\update\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\region\update\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\UpdateAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Region::class,
            ],
            'delete' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\region\delete\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\region\delete\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\DeleteAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Region::class,
            ],
        ];
    }

}
