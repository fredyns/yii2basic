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
                'class' => \app\lib\IndexAction::class,
                'searchClass' => \app\lib\sample\person\index\PersonSearch::class,
            ],
            'view' => [
                'class' => \app\lib\ViewAction::class,
                'modelClass' => \app\models\sample\Person::class,
            ],
            'create' => [
                'class' => \app\lib\CreateAction::class,
                'modelClass' => \app\models\sample\Person::class,
            ],
            'update' => [
                'class' => \app\lib\UpdateAction::class,
                'modelClass' => \app\models\sample\Person::class,
            ],
            'delete' => [
                'class' => \app\lib\DeleteAction::class,
                'modelClass' => \app\models\sample\Person::class,
            ],
        ];
    }

}