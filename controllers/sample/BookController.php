<?php

namespace app\controllers\sample;

use Yii;
use yii\web\Controller;

/**
 * This is the class for controller "BookController".
 */
class BookController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\book\index\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\book\index\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\IndexAction::class,
                'searchClass' => [
                    'class' => \app\actions\sample\book\PersonSearch::class,
                    'is_deleted' => 0,
                ],
            ],
            'view' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\book\view\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\book\view\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\ViewAction::class,
                'modelClass' => \app\models\sample\Book::class,
            ],
            'create' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\book\create\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\book\create\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\CreateAction::class,
                'modelClass' => \app\models\sample\Book::class,
            ],
            'update' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\book\update\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\book\update\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\UpdateAction::class,
                'modelClass' => \app\models\sample\Book::class,
            ],
            'delete' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\book\delete\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\book\delete\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\DeleteAction::class,
                'modelClass' => \app\models\sample\Book::class,
            ],
            'restore' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\book\restore\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\book\restore\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\RestoreAction::class,
                'modelClass' => \app\models\sample\Book::class,
            ],
            'deleted' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\book\deleted\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\book\deleted\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\IndexAction::class,
                'searchClass' => [
                    'class' => \app\actions\sample\book\PersonSearch::class,
                    'is_deleted' => 1,
                ],
                'view' => 'deleted',
            ],
            'archive' => [
                //  uncomment thesse for custom action & access control
                //  'class' => \app\actions\sample\book\archive\ActiveAction::class,
                //  'actionControl' => \app\actions\sample\book\archive\ActionControl::class,
                //
                //  generic action
                'class' => \app\lib\IndexAction::class,
                'searchClass' => [
                    'class' => \app\actions\sample\book\PersonSearch::class,
                ],
                'view' => 'archive',
            ],
        ];
    }

}
