<?php

namespace app\models\sample;

use Yii;
use app\models\Profile;

/**
 * This is the model class for table "sample_person".
 * define model structure as specified in database.
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property integer $is_deleted
 * @property integer $deleted_at
 * @property integer $deleted_by
 * @property string $name
 *
 * @property Profile $createdBy
 * @property Profile $updatedBy
 * @property Profile $deletedBy
 *
 * @property \app\models\sample\Book[] $booksAsAuthor
 * @property \app\models\sample\Book[] $booksAsEditor
 *
 * @method void softDelete() move to trash
 * @method void restore() bring back form trash
 */
class Person extends \yii\db\ActiveRecord
{
    const CREATEDBY = 'createdBy';
    const UPDATEDBY = 'updatedBy';
    const DELETEDBY = 'deletedBy';

    /* -------------------------- Static -------------------------- */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sample_person';
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
        return $plural ? Yii::t('app/sample/models', 'People') : Yii::t('app/sample/models', 'Person');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('record-info', 'ID'),
            'created_at' => Yii::t('record-info', 'Created At'),
            'created_by' => Yii::t('record-info', 'Created By'),
            'updated_at' => Yii::t('record-info', 'Updated At'),
            'updated_by' => Yii::t('record-info', 'Updated By'),
            'is_deleted' => Yii::t('record-info', 'Is Deleted'),
            'deleted_at' => Yii::t('record-info', 'Deleted At'),
            'deleted_by' => Yii::t('record-info', 'Deleted By'),
            'name' => Yii::t('app/sample/models', 'Name'),
        ];
    }
    ##

    /* -------------------------- Meta -------------------------- */

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'blameable' => [
                'class' => \yii\behaviors\BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
            'softdelete' => [
                'class' => \yii2tech\ar\softdelete\SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'is_deleted' => TRUE,
                    'deleted_at' => time(),
                    'deleted_by' => function($model) {
                        if (Yii::$app->user->isGuest === FALSE) {
                            return Yii::$app->user->id;
                        }

                        return NULL;
                    },
                ],
                'restoreAttributeValues' => [
                    'is_deleted' => FALSE,
                ],
            ],
        ];
    }

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
            [['name'], 'string', 'max' => 255],
            # format,
            # restriction,
            # constraint,
            # safe,
        ];
    }
    ##

    /* -------------------------- Properties -------------------------- */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Profile::class, ['id' => 'created_by'])->alias(static::CREATEDBY);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(Profile::class, ['id' => 'updated_by'])->alias(static::UPDATEDBY);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(Profile::class, ['id' => 'deleted_by'])->alias(static::DELETEDBY);
    }
    ##

    /* -------------------------- Has Many -------------------------- */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooksAsAuthor($filter = ['is_deleted' => FALSE])
    {
        return $this
                ->hasMany(Book::class, ['author_id' => 'id'])
                ->andFilterWhere($filter)
        ;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooksAsEditor($filter = ['is_deleted' => FALSE])
    {
        return $this
                ->hasMany(Book::class, ['editor_id' => 'id'])
                ->andFilterWhere($filter)
        ;
    }
    ##

}