<?php

namespace app\models\geographical_hierarchy;

use Yii;
use app\models\User;

/**
 * This is the base-model class for table "geographical_hierarchy_type".
 * define base model structure as specified in database.
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 *
 *
 * @property \app\models\geographical_hierarchy\City[] $cities
 * @property \app\models\geographical_hierarchy\District[] $districts
 * @property \app\models\geographical_hierarchy\Region[] $regions
 * @property \app\models\geographical_hierarchy\Subdistrict[] $subdistricts
 */
class Type extends \yii\db\ActiveRecord
{

    /* -------------------------- Static -------------------------- */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geographical_hierarchy_type';
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
        return $plural ? Yii::t('geographical_hierarchy', 'Types') : Yii::t('geographical_hierarchy', 'Type');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('record-info', 'ID'),
            'name' => Yii::t('geographical_hierarchy', 'Name'),
            'description' => Yii::t('geographical_hierarchy', 'Description'),
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