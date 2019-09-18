<?php

namespace app\controllers\api\geo_address;

/**
 * This is the class for REST controller "TypeController".
 */
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\lib\geo_address\type\TypeAC;

class TypeController extends \yii\rest\ActiveController
{
    public $modelClass = \app\models\geo_address\Type::class;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return ArrayHelper::merge(
                parent::actions(),
                [
                'select2-options' => [
                    'class' => \app\components\Select2Options::class,
                    'modelClass' => $this->modelClass,
                    'text_field' => 'name',
                ],
                ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        TypeAC::catchError();

        $allow = TypeAC::can($action);

        if (!$allow) {
            throw TypeAC::exception();
        }
    }

}