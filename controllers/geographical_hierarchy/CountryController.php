<?php

namespace app\controllers\geographical_hierarchy;

use Yii;
use yii\web\Controller;

/**
 * This is the class for controller "CountryController".
 */
class CountryController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\country\index\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\country\index\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\IndexAction::class,
                'searchClass' => [
                    'class' => \app\actions\geographical_hierarchy\country\CountrySearch::class,
                ],
            ],
            'view' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\country\view\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\country\view\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\ViewAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Country::class,
            ],
            'create' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\country\create\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\country\create\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\CreateAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Country::class,
            ],
            'update' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\country\update\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\country\update\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\UpdateAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Country::class,
            ],
            'delete' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\country\delete\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\country\delete\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\DeleteAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Country::class,
            ],
        ];
    }

}
