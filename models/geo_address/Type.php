<?php

namespace app\models\geo_address;

use Yii;
use app\models\Profile;

/**
 * This is the model class for table "geo_address_type".
 * define model structure as specified in database.
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 *
 *
 * @property \app\models\geo_address\City[] $cities
 * @property \app\models\geo_address\District[] $districts
 * @property \app\models\geo_address\Region[] $regions
 * @property \app\models\geo_address\Subdistrict[] $subdistricts
 */
class Type extends \yii\db\ActiveRecord
{

    /* -------------------------- Static -------------------------- */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geo_address_type';
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
        return $plural ? Yii::t('app/geo_address/models', 'Types') : Yii::t('app/geo_address/models', 'Type');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('record-info', 'ID'),
            'name' => Yii::t('app/geo_address/models', 'Name'),
            'description' => Yii::t('app/geo_address/models', 'Description'),
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
                ['name', 'description'],
                \fredyns\stringcleaner\yii2\PlaintextValidator::class,
            ],
            # default,
            # required,
            # type,
            [['description'], 'string'],
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

    /* -------------------------- Has Many -------------------------- */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this
                ->hasMany(City::class, ['type_id' => 'id'])
        ;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistricts()
    {
        return $this
                ->hasMany(District::class, ['type_id' => 'id'])
        ;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegions()
    {
        return $this
                ->hasMany(Region::class, ['type_id' => 'id'])
        ;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubdistricts()
    {
        return $this
                ->hasMany(Subdistrict::class, ['type_id' => 'id'])
        ;
    }
    ##

}