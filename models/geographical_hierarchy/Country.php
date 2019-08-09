<?php

namespace app\models\geographical_hierarchy;

use Yii;
use app\models\User;

/**
 * This is the base-model class for table "geographical_hierarchy_country".
 * define base model structure as specified in database.
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 *
 */
class Country extends \yii\db\ActiveRecord
{

    /* -------------------------- Static -------------------------- */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geographical_hierarchy_country';
    }
    ##

    /* -------------------------- Labels -------------------------- */

    /**
     * model label as display title
     *
     * @return string
     */
    public function modelLabel($plural = false)
    {
        return $plural ? Yii::t('geographical_hierarchy', 'Countries') : Yii::t('geographical_hierarchy', 'Country');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('record-info', 'ID'),
            'name' => Yii::t('geographical_hierarchy', 'Name'),
            'code' => Yii::t('geographical_hierarchy', 'Code'),
        ];
    }
    ##

    /* -------------------------- Meta -------------------------- */

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            # filter,
            [
                ['name', 'code'],
                \fredyns\stringcleaner\yii2\PlaintextValidator::class,
            ],
            # default,
            # required,
            # type,
            [['code'], 'string', 'max' => 8],
            [['name'], 'string', 'max' => 255],
            # format,
            # restriction,
            [['code'], 'unique'],
            # constraint,
            # safe,
        ];
    }
    ##

    /* -------------------------- Properties -------------------------- */
    ##

}