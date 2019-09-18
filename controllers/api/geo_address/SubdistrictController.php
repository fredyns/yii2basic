<?php

namespace app\controllers\api\geo_address;

/**
 * This is the class for REST controller "SubdistrictController".
 */
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\lib\geo_address\subdistrict\SubdistrictAC;

class SubdistrictController extends \yii\rest\ActiveController
{
    public $modelClass = \app\models\geo_address\Subdistrict::class;

    /**
     * {@inheritdoc}
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        SubdistrictAC::catchError();

        $allow = SubdistrictAC::can($action);

        if (!$allow) {
            throw SubdistrictAC::exception();
        }
    }

}