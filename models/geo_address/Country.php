<?php

namespace app\models\geo_address;

use Yii;
use app\models\Profile;

/**
 * This is the model class for table "geo_address_country".
 * define model structure as specified in database.
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 *
 *
 * @property \app\models\geo_address\City[] $cities
 * @property \app\models\geo_address\Region[] $regions
 */
class Country extends \yii\db\ActiveRecord
{

    /* -------------------------- Static -------------------------- */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geo_address_country';
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
        return $plural ? Yii::t('app/geo_address/models', 'Countries') : Yii::t('app/geo_address/models', 'Country');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('record-info', 'ID'),
            'name' => Yii::t('app/geo_address/models', 'Name'),
            'code' => Yii::t('app/geo_address/models', 'Code'),
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

    /* -------------------------- Has Many -------------------------- */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this
                ->hasMany(City::class, ['country_id' => 'id'])
        ;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegions()
    {
        return $this
                ->hasMany(Region::class, ['country_id' => 'id'])
        ;
    }
    ##

}