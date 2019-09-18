<?php

namespace app\models\geo_address;

use Yii;
use app\models\Profile;

/**
 * This is the model class for table "geo_address_district".
 * define model structure as specified in database.
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
 * @property \app\models\geo_address\City $city
 * @property \app\models\geo_address\Type $type
 *
 * @property \app\models\geo_address\Subdistrict[] $subdistricts
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
        return 'geo_address_district';
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
        return $plural ? Yii::t('app/geo_address/models', 'Districts') : Yii::t('app/geo_address/models', 'District');
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
            'city_id' => Yii::t('app/geo_address/models', 'City'),
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
            [['type_id', 'city_id', 'reg_number'], 'integer'],
            [['name'], 'string', 'max' => 255],
            # format,
            # restriction,
            # constraint,
            [
                ['city_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => City::class,
                'targetAttribute' => ['city_id' => 'id'],
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

    /* -------------------------- Has Many -------------------------- */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubdistricts()
    {
        return $this
                ->hasMany(Subdistrict::class, ['district_id' => 'id'])
        ;
    }
    ##

}