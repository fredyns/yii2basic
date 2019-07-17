<?php

namespace app\controllers\sample\api;

/**
 * This is the class for REST controller "PersonController".
 */
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class PersonController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\sample\Person';

}