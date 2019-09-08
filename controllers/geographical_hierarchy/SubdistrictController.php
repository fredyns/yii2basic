<?php

namespace app\controllers\geographical_hierarchy;

use Yii;
use yii\web\Controller;

/**
 * This is the class for controller "SubdistrictController".
 */
class SubdistrictController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\subdistrict\index\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\subdistrict\index\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\IndexAction::class,
                'searchClass' => [
                    'class' => \app\actions\geographical_hierarchy\subdistrict\SubdistrictSearch::class,
                ],
            ],
            'view' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\subdistrict\view\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\subdistrict\view\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\ViewAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Subdistrict::class,
            ],
            'create' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\subdistrict\create\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\subdistrict\create\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\CreateAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Subdistrict::class,
            ],
            'update' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\subdistrict\update\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\subdistrict\update\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\UpdateAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Subdistrict::class,
            ],
            'delete' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\geographical_hierarchy\subdistrict\delete\ActiveAction::class,
                //  'actionControl' => \app\actions\geographical_hierarchy\subdistrict\delete\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\DeleteAction::class,
                'modelClass' => \app\models\geographical_hierarchy\Subdistrict::class,
            ],
        ];
    }

}
