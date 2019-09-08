<?php

namespace app\controllers\sample;

use Yii;
use yii\web\Controller;

/**
 * This is the class for controller "PersonController".
 */
class PersonController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\person\index\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\person\index\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\IndexAction::class,
                'searchClass' => [
                    'class' => \app\actions\sample\person\PersonSearch::class,
                    'is_deleted' => 0,
                ],
            ],
            'view' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\person\view\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\person\view\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\ViewAction::class,
                'modelClass' => \app\models\sample\Person::class,
            ],
            'create' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\person\create\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\person\create\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\CreateAction::class,
                'modelClass' => \app\models\sample\Person::class,
            ],
            'update' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\person\update\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\person\update\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\UpdateAction::class,
                'modelClass' => \app\models\sample\Person::class,
            ],
            'delete' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\person\delete\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\person\delete\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\DeleteAction::class,
                'modelClass' => \app\models\sample\Person::class,
            ],
            'restore' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\person\restore\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\person\restore\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\RestoreAction::class,
                'modelClass' => \app\models\sample\Person::class,
            ],
            'deleted' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\person\deleted\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\person\deleted\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\IndexAction::class,
                'searchClass' => [
                    'class' => \app\actions\sample\person\PersonSearch::class,
                    'is_deleted' => 1,
                ],
                'view' => 'deleted',
            ],
            'archive' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\person\archive\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\person\archive\ActionControl::class,
                //
                //  generic action
                'class' => \app\components\IndexAction::class,
                'searchClass' => [
                    'class' => \app\actions\sample\person\PersonSearch::class,
                ],
                'view' => 'archive',
            ],
        ];
    }

}
