<?php

namespace app\controllers\api\sample;

/**
 * This is the class for REST controller "BookController".
 */
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\lib\sample\book\BookAC;

class BookController extends \yii\rest\ActiveController
{
    public $modelClass = \app\models\sample\Book::class;

    /**
     * {@inheritdoc}
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        BookAC::catchError();

        $allow = BookAC::can($action);

        if (!$allow) {
            throw BookAC::exception();
        }
    }

}