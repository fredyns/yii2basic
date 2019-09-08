<?php

namespace app\controllers\geographical_hierarchy;

use Yii;
use yii\web\Controller;

/**
 * This is the class for controller "TypeController".
 */
class TypeController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\type\index\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\type\index\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\IndexAction::class,
                'searchClass' => [
                    'class' => \app\actions\geographical_hierarchy\type\TypeSearch::class,
                ],
            ],
            'view' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\type\view\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\type\view\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\ViewAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Type::class,
            ],
            'create' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\type\create\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\type\create\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\CreateAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Type::class,
            ],
            'update' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\type\update\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\type\update\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\UpdateAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Type::class,
            ],
            'delete' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\type\delete\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\type\delete\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\DeleteAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Type::class,
            ],
        ];
    }

}
