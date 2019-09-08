<?php

namespace app\controllers\geographical_hierarchy;

use Yii;
use yii\web\Controller;

/**
 * This is the class for controller "DistrictController".
 */
class DistrictController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\district\index\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\district\index\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\IndexAction::class,
                'searchClass' => [
                    'class' => \app\actions\geographical_hierarchy\district\DistrictSearch::class,
                ],
            ],
            'view' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\district\view\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\district\view\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\ViewAction::class,
                'modelClass' => \app\models\geographical_hierarchy\District::class,
            ],
            'create' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\district\create\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\district\create\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\CreateAction::class,
                'modelClass' => \app\models\geographical_hierarchy\District::class,
            ],
            'update' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\district\update\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\district\update\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\UpdateAction::class,
                'modelClass' => \app\models\geographical_hierarchy\District::class,
            ],
            'delete' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\district\delete\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\district\delete\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\DeleteAction::class,
                'modelClass' => \app\models\geographical_hierarchy\District::class,
            ],
        ];
    }

}
