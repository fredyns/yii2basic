<?php

namespace app\models\geo_address;

use Yii;
use app\models\Profile;

/**
 * This is the model class for table "geo_address_subdistrict".
 * define model structure as specified in database.
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 *
 * @property integer $id
 * @property string $name
 * @property integer $type_id
 * @property integer $district_id
 * @property string $reg_number
 *
 *
 * @property \app\models\geo_address\District $district
 * @property \app\models\geo_address\Type $type
 */
class Subdistrict extends \yii\db\ActiveRecord
{
    const DISTRICT = 'district';
    const TYPE = 'type';

    /* -------------------------- Static -------------------------- */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geo_address_subdistrict';
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
        return $plural ? Yii::t('app/geo_address/models', 'Subdistricts') : Yii::t('app/geo_address/models', 'Subdistrict');
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
            'district_id' => Yii::t('app/geo_address/models', 'District'),
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
            [['type_id', 'district_id', 'reg_number'], 'integer'],
            [['name'], 'string', 'max' => 255],
            # format,
            # restriction,
            # constraint,
            [
                ['district_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => District::class,
                'targetAttribute' => ['district_id' => 'id'],
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
    public function getDistrict()
    {
        return $this->hasOne(District::class, ['id' => 'district_id'])->alias(static::DISTRICT);
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