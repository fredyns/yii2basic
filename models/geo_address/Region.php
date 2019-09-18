<?php

namespace app\models\geo_address;

use Yii;
use app\models\Profile;

/**
 * This is the model class for table "geo_address_region".
 * define model structure as specified in database.
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 *
 * @property integer $id
 * @property string $name
 * @property integer $type_id
 * @property integer $country_id
 * @property integer $reg_number
 *
 *
 * @property \app\models\geo_address\Country $country
 * @property \app\models\geo_address\Type $type
 *
 * @property \app\models\geo_address\City[] $cities
 */
class Region extends \yii\db\ActiveRecord
{
    const COUNTRY = 'country';
    const TYPE = 'type';

    /* -------------------------- Static -------------------------- */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geo_address_region';
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
        return $plural ? Yii::t('app/geo_address/models', 'Regions') : Yii::t('app/geo_address/models', 'Region');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('record-info', 'ID'),
            'name' => Yii::t('app/geo_address/models', 'Name'),
            'type_id' => Yii::t('app/geo_address/models', 'Type'),
            'country_id' => Yii::t('app/geo_address/models', 'Country'),
            'reg_number' => Yii::t('app/geo_address/models', 'Reg Number'),
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
            [['type_id', 'country_id', 'reg_number'], 'integer'],
            [['name'], 'string', 'max' => 255],
            # format,
            # restriction,
            # constraint,
            [
                ['country_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Country::class,
                'targetAttribute' => ['country_id' => 'id'],
            ],
            [
                ['type_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Type::class,
                'targetAttribute' => ['type_id' => 'id'],
            ],
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
    public function getType()
    {
        return $this->hasOne(Type::class, ['id' => 'type_id'])->alias(static::TYPE);
    }
    ##

    /* -------------------------- Has Many -------------------------- */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this
                ->hasMany(City::class, ['region_id' => 'id'])
        ;
    }
    ##

}