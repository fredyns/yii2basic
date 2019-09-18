<?php

namespace app\models\sample;

use Yii;
use app\models\Profile;

/**
 * This is the model class for table "sample_book".
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
 * @property string $title
 * @property string $description
 * @property integer $author_id
 * @property integer $editor_id
 * @property string $released_date
 *
 * @property Profile $createdBy
 * @property Profile $updatedBy
 * @property Profile $deletedBy
 *
 * @property \app\models\sample\Person $author
 * @property \app\models\sample\Person $editor
 *
 * @method void softDelete() move to trash
 * @method void restore() bring back form trash
 */
class Book extends \yii\db\ActiveRecord
{
    const CREATEDBY = 'createdBy';
    const UPDATEDBY = 'updatedBy';
    const DELETEDBY = 'deletedBy';
    const AUTHOR = 'author';
    const EDITOR = 'editor';

    /* -------------------------- Static -------------------------- */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sample_book';
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
        return $plural ? Yii::t('app/sample/models', 'Books') : Yii::t('app/sample/models', 'Book');
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
            'title' => Yii::t('app/sample/models', 'Title'),
            'description' => Yii::t('app/sample/models', 'Description'),
            'author_id' => Yii::t('app/sample/models', 'Author'),
            'editor_id' => Yii::t('app/sample/models', 'Editor'),
            'released_date' => Yii::t('app/sample/models', 'Released Date'),
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
                ['title', 'description'],
                \fredyns\stringcleaner\yii2\PlaintextValidator::class,
            ],
            # default,
            # required,
            # type,
            [['title', 'description'], 'string'],
            [['author_id', 'editor_id'], 'integer'],
            # format,
            [['released_date'], 'date', 'format' => 'yyyy-MM-dd'],
            # restriction,
            # constraint,
            [
                ['author_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Person::class,
                'targetAttribute' => ['author_id' => 'id'],
            ],
            [
                ['editor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Person::class,
                'targetAttribute' => ['editor_id' => 'id'],
            ],
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

    /* -------------------------- Has One -------------------------- */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Person::class, ['id' => 'author_id'])->alias(static::AUTHOR);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEditor()
    {
        return $this->hasOne(Person::class, ['id' => 'editor_id'])->alias(static::EDITOR);
    }
    ##

}