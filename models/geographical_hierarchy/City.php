<?php

namespace app\models\geographical_hierarchy;

use Yii;
use app\models\User;

/**
 * This is the base-model class for table "geographical_hierarchy_city".
 * define base model structure as specified in database.
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 *
 * @property integer $id
 * @property string $name
 * @property integer $type_id
 * @property integer $country_id
 * @property integer $region_id
 * @property integer $reg_number
 *
 *
 * @property \app\models\geographical_hierarchy\Country $country
 * @property \app\models\geographical_hierarchy\Region $region
 * @property \app\models\geographical_hierarchy\Type $type
 *
 * @property \app\models\geographical_hierarchy\District[] $districts
 */
class City extends \yii\db\ActiveRecord
{
    const COUNTRY = 'country';
    const REGION = 'region';
    const TYPE = 'type';

    /* -------------------------- Static -------------------------- */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geographical_hierarchy_city';
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
        return $plural ? Yii::t('geographical_hierarchy', 'Cities') : Yii::t('geographical_hierarchy', 'City');
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
            'country_id' => Yii::t('geographical_hierarchy', 'Country'),
            'region_id' => Yii::t('geographical_hierarchy', 'Region'),
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
            [['type_id', 'country_id', 'region_id', 'reg_number'], 'integer'],
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
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id'])->alias(static::COUNTRY);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::class, ['id' => 'region_id'])->alias(static::REGION);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::class, ['id' => 'type_id'])->alias(static::TYPE);
    }
    ##

    /* -------------------------- Has Many -------------------------- */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistricts()
    {
        return $this
                ->hasMany(District::class, ['city_id' => 'id'])
        ;
    }
    ##

}