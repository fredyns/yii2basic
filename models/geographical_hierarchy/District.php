<?php

namespace app\models\geographical_hierarchy;

use Yii;
use app\models\User;

/**
 * This is the base-model class for table "geographical_hierarchy_district".
 * define base model structure as specified in database.
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 *
 * @property integer $id
 * @property string $name
 * @property integer $type_id
 * @property integer $city_id
 * @property integer $reg_number
 *
 *
 * @property \app\models\geographical_hierarchy\City $city
 * @property \app\models\geographical_hierarchy\Type $type
 */
class District extends \yii\db\ActiveRecord
{
    const CITY = 'city';
    const TYPE = 'type';

    /* -------------------------- Static -------------------------- */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geographical_hierarchy_district';
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
        return $plural ? Yii::t('geographical_hierarchy', 'Districts') : Yii::t('geographical_hierarchy', 'District');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('record-info', 'ID'),
            'name' => Yii::t('geographical_hierarchy', 'Name'),
            'type_id' => Yii::t('geographical_hierarchy', 'Type'),
            'city_id' => Yii::t('geographical_hierarchy', 'City'),
            'reg_number' => Yii::t('geographical_hierarchy', 'Reg Number'),
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
                ['name'],
                \fredyns\stringcleaner\yii2\PlaintextValidator::class,
            ],
            # default,
            # required,
            # type,
            [['type_id', 'city_id', 'reg_number'], 'integer'],
            [['name'], 'string', 'max' => 255],
            # format,
            # restriction,
            # constraint,
            # safe,
        ];
    }
    ##

    /* -------------------------- Properties -------------------------- */
    ##

    /* -------------------------- Has One -------------------------- */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id'])->alias(static::CITY);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::class, ['id' => 'type_id'])->alias(static::TYPE);
    }
    ##

}