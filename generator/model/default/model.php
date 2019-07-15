<?php

use yii\helpers\Inflector;

/* @var $this yii\web\View  */
/* @var $generator app\generator\model\Generator  */
/* @var $tableSchema yii\db\TableSchema  */
/* @var $tableName string  full table name */
/* @var $db string  */
/* @var $nameSpace string  */
/* @var $baseClass string  */
/* @var $className string  class name */
/* @var $labels string[]  */
/* @var $hints string[]  */
/* @var $enableI18N boolean  */
/* @var $messageCategory string  */
/* @var $rules string[]  list of validation rules */
/* @var $hasOne array  */
/* @var $hasMany array  */
/* @var $hasJunction array  */

$modelName = Inflector::camel2words($className);
$blameable = ($tableSchema->getColumn('created_by') !== null) OR ($tableSchema->getColumn('updated_by') !== null);
$timestamp = ($tableSchema->getColumn('created_at') !== null) OR ($tableSchema->getColumn('updated_at') !== null);
$softdelete = ($tableSchema->getColumn('is_deleted') !== null) && ($tableSchema->getColumn('deleted_at') !== null) && ($tableSchema->getColumn('deleted_by') !== null);

echo "<?php\n";
?>

namespace <?= $nameSpace ?>;

use Yii;
use app\models\User;

/**
 * This is the base-model class for table "<?= $tableName ?>".
 * define base model structure as specified in database.
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
 *
<?php if ($tableSchema->getColumn('created_by') !== null): ?>
 * @property User $createdBy
<?php endif; ?>
<?php if ($tableSchema->getColumn('updated_by') !== null): ?>
 * @property User $updatedBy
<?php endif; ?>
<?php if ($tableSchema->getColumn('deleted_by') !== null): ?>
 * @property User $deletedBy
<?php endif; ?>
<?php if (isset($hasOne) && !empty($hasOne)): ?>
<?php foreach ($hasOne as $name => $relation): ?>
 * @property \<?= $relation['nameSpace'].'\\'.$relation['className'].' $'.lcfirst($name)."\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 *
<?php if (isset($hasMany) && !empty($hasMany)): ?>
<?php foreach ($hasMany as $name => $relation): ?>
 * @property \<?= $relation['nameSpace'].'\\'.$relation['className'].'[] $'.lcfirst($name)."\n" ?>
<?php endforeach; ?>
<?php endif; ?>
<?php if (isset($hasJunction) && !empty($hasJunction)): ?>
<?php foreach ($hasJunction as $name => $relation): ?>
 * @property \<?= $relation['nameSpace'].'\\'.$relation['className'].'[] $'.lcfirst($name)."\n" ?>
<?php endforeach; ?>
<?php endif; ?>
<?php if ($softdelete): ?>
 *
 * @method void softDelete() move to trash
 * @method void restore() bring back form trash
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($baseClass, '\\') . "\n" ?>
{
<?php if ($tableSchema->getColumn('created_by') !== null): ?>
    const CREATEDBY = 'createdBy';
<?php endif; ?>
<?php if ($tableSchema->getColumn('updated_by') !== null): ?>
    const UPDATEDBY = 'updatedBy';
<?php endif; ?>
<?php if ($tableSchema->getColumn('deleted_by') !== null): ?>
    const DELETEDBY = 'deletedBy';
<?php endif; ?>
<?php if (isset($hasOne) && !empty($hasOne)): ?>
<?php foreach ($hasOne as $name => $relation): ?>
<?php if ($relation['alias']): ?>
    const <?= strtoupper($name) ?> = '<?= strtolower($name) ?>';
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php if (isset($hasMany) && !empty($hasMany)): ?>
<?php foreach ($hasMany as $name => $relation): ?>
<?php if ($relation['alias']): ?>
    const <?= strtoupper($name) ?> = '<?= strtolower($name) ?>';
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

    /* -------------------------- Static -------------------------- */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $tableName ?>';
    }
<?php if ($db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $db ?>');
    }
<?php endif; ?>
    ##

    /* -------------------------- Labels -------------------------- */

    /**
     * model label as display title
     *
     * @return string
     */
    public function modelLabel($plural = false)
    {
        return $plural ? <?= $generator->generateString(Inflector::pluralize($modelName)) ?> : <?= $generator->generateString($modelName) ?>;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php
foreach ($labels as $name => $label) {
    $label = $generator->generateString($label);
    echo "            '{$name}' => {$label},\n";
}
?>
        ];
    }
<?php if (!empty($hints)): ?>

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
<?php foreach ($hints as $name => $hint): ?>
            <?= "'$name' => " . $generator->generateString($hint) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php endif; ?>
    ##

    /* -------------------------- Meta -------------------------- */
<?php if ($blameable OR $timestamp OR $softdelete): ?>

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
<?php if ($blameable): ?>
            'blameable' => [
                'class' => \yii\behaviors\BlameableBehavior::class,
                'createdByAttribute' => <?= ($tableSchema->getColumn('created_by') !== null) ? "'created_by'" : "false" ?>,
                'updatedByAttribute' => <?= ($tableSchema->getColumn('updated_by') !== null) ? "'updated_by'" : "false" ?>,
            ],
<?php endif; ?>
<?php if ($timestamp): ?>
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::class,
                'createdAtAttribute' => <?= ($tableSchema->getColumn('created_at') !== null) ? "'created_at'" : "false" ?>,
                'updatedAtAttribute' => <?= ($tableSchema->getColumn('updated_at') !== null) ? "'updated_at'" : "false" ?>,
            ],
<?php endif; ?>
<?php if ($softdelete): ?>
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
<?php endif; ?>
        ];
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . ",\n        " ?>];
    }
    ##

    /* -------------------------- Properties -------------------------- */
<?php if ($tableSchema->getColumn('created_by') !== null): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by'])->alias(static::CREATEDBY);
    }
<?php endif; ?>
<?php if ($tableSchema->getColumn('updated_by') !== null): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by'])->alias(static::UPDATEDBY);
    }
<?php endif; ?>
<?php if ($tableSchema->getColumn('deleted_by') !== null): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(User::class, ['id' => 'deleted_by'])->alias(static::DELETEDBY);
    }
<?php endif; ?>
<?php if (isset($hasOne) && !empty($hasOne)): ?>
    ##

    /* -------------------------- Has One -------------------------- */
<?php foreach ($hasOne as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation['query'] . "\n" ?>
    }
<?php endforeach; ?>
<?php endif; ?>
<?php if (isset($hasMany) && !empty($hasMany)): ?>
    ##

    /* -------------------------- Has Many -------------------------- */
<?php foreach ($hasMany as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
<?php if (strpos($relation['query'], '\$filter')!==FALSE): ?>
    public function get<?= $name ?>($filter = ['is_deleted' => FALSE])
<?php else: ?>
    public function get<?= $name ?>()
<?php endif; ?>
    {
        <?= $relation['query'] . "\n" ?>
    }
<?php endforeach; ?>
<?php endif; ?>
<?php if (isset($hasJunction) && !empty($hasJunction)): ?>
    ##

    /* -------------------------- Has Junction -------------------------- */
<?php foreach ($hasJunction as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation['query'] . "\n" ?>
    }
<?php endforeach; ?>
<?php endif; ?>
    ##

}