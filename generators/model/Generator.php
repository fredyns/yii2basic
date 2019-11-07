<?php

namespace app\generators\model;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ColumnSchema;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use app\generators\SaveForm;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 *
 * @since 0.0.1
 */
class Generator extends \schmunk42\giiant\generators\model\Generator
{
    public $tableName = '*';

    /**
     * @var bool whether the strings will be generated using `Yii::t()` or normal strings.
     */
    public $enableI18N = true;

    /**
     * @var bool whether to overwrite (extended) model classes, will be always created, if file does not exist
     */
    public $generateModelClass = true;

    /**
     * @var bool whether or not to use TimestampBehavior
     */
    public $useTimestampBehavior = true;

    /**
     * @var bool whether or not to use 2amigos/yii2-translateable-behavior
     */
    public $useTranslatableBehavior = false;

    /**
     * @var bool This indicates whether the generator should generate attribute hints by using the comments of the corresponding DB columns
     */
    public $generateHintsFromComments = false;

    /**
     * @var string[]
     */
    protected $modelClasses = [];

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'My Model';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'My generator generates an ActiveRecord class and base class for the specified database table.';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // gii generator
            [['template'], 'required', 'message' => 'A code template must be selected.'],
            [['template'], 'validateTemplate'],
            // gii model rules
            [['db', 'ns', 'tableName', 'modelClass', 'baseClass', 'queryNs', 'queryClass', 'queryBaseClass'], 'filter', 'filter' => 'trim'],
            [
                ['ns', 'queryNs'],
                'filter',
                'filter' => function ($value) {
                    return trim($value, '\\');
                },
            ],
            //[['db', 'ns', 'tableName', 'baseClass', 'queryNs', 'queryBaseClass'], 'required'],// original line
            [['db', 'ns', 'baseClass', 'queryNs', 'queryBaseClass'], 'required'],
            [['db', 'modelClass', 'queryClass'], 'match', 'pattern' => '/^\w+$/', 'message' => 'Only word characters are allowed.'],
            [['ns', 'baseClass', 'queryNs', 'queryBaseClass'], 'match', 'pattern' => '/^[\w\\\\]+$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['tableName'], 'match', 'pattern' => '/^([\w ]+\.)?([\w\* ]+)$/', 'message' => 'Only word characters, and optionally spaces, an asterisk and/or a dot are allowed.'],
            [['db'], 'validateDb'],
            [['ns', 'queryNs'], 'validateNamespace'],
            [['tableName'], 'validateTableName'],
            [['modelClass'], 'validateModelClass', 'skipOnEmpty' => false],
            [['baseClass'], 'validateClass', 'params' => ['extends' => ActiveRecord::className()]],
            [['queryBaseClass'], 'validateClass', 'params' => ['extends' => ActiveQuery::className()]],
            [['generateRelations'], 'in', 'range' => [self::RELATIONS_NONE, self::RELATIONS_ALL, self::RELATIONS_ALL_INVERSE]],
            [['generateLabelsFromComments', 'useTablePrefix', 'useSchemaName', 'generateQuery', 'generateRelationsFromCurrentSchema'], 'boolean'],
            [['enableI18N', 'standardizeCapitals', 'singularize'], 'boolean'],
            [['messageCategory'], 'validateMessageCategory', 'skipOnEmpty' => false],
            // giiant rules
            [[
                'generateModelClass',
                'useTranslatableBehavior',
                'generateHintsFromComments',
                'useBlameableBehavior',
                'useTimestampBehavior',
                'singularEntities',
                ], 'boolean'],
            [['languageTableName', 'languageCodeColumn', 'createdByColumn', 'updatedByColumn', 'createdAtColumn', 'updatedAtColumn', 'savedForm'], 'string'],
            [['tablePrefix'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function requiredTemplates()
    {
        return ['model.php'];
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $this->enableI18N = TRUE;
        $this->modelClasses = \app\generators\tableclass\Generator::readData();
        $files = [];
        $relations = $this->generateRelations();

        foreach ($this->getTableNames() as $tableName) {
            $params = $this->generateParams($tableName, $relations);

            if ($this->tableName == '*') {
                $this->messageCategory = $params['messageCategory'];
            }

            $modelClassFile = Yii::getAlias('@'.str_replace('\\', '/', $params['nameSpace'])).'/'.$params['className'].'.php';
            $files[] = new CodeFile(
                $modelClassFile, $this->render('model.php', $params)
            );

            if ($this->tableName != '*') {
                /*
                 * create gii/[name]GiiantModel.json with actual form data
                 * only when customized
                 */
                $suffix = '_'.str_replace(' ', '', $this->getName());
                $formDataDir = Yii::getAlias('@'.str_replace('\\', '/', $this->ns));
                $formDataFile = StringHelper::dirname($formDataDir).'/gii/'.$tableName.$suffix.'.json';
                $generatorForm = (clone $this);
                $generatorForm->tableName = $tableName;
                $generatorForm->modelClass = $params['className'];
                $formData = json_encode(SaveForm::getFormAttributesValues($generatorForm, $this->formAttributes()), JSON_PRETTY_PRINT);
                $files[] = new CodeFile($formDataFile, $formData);
            }
        }

        return $files;
    }

    public function generateParams($tableName, $relations)
    {
        if ($this->modelClass === '' || php_sapi_name() === 'cli') {
            // try to get file
            $suffix = str_replace(' ', '', $this->getName());
            $formDataPath = Yii::getAlias('@app')."/gii/{$tableName}_{$suffix}.json";
            $formDataContent = file_get_contents($formDataPath);
            $formData = $formDataContent ? (array) json_decode($formDataContent, true) : [];
            $className = ArrayHelper::getValue($formData, 'modelclass.value', $this->generateClassName($tableName));
            $nameSpace = ArrayHelper::getValue($formData, 'ns.value', $this->ns);
            $messageCategory = ArrayHelper::getValue($formData, 'messagecategory.value', $this->messageCategory);
        } else {
            $className = $this->modelClass;
            $nameSpace = $this->ns;
            $messageCategory = $this->messageCategory;
        }

        // compose
        $db = $this->getDbConnection();
        $tableSchema = $db->getTableSchema($tableName);

        return [
            'tableName' => $tableName,
            'className' => $className,
            'tableSchema' => $tableSchema,
            'labels' => $this->generateLabels($tableSchema),
            'rules' => $this->generateRules($tableSchema),
            'relations' => isset($relations[$tableName]) ? $relations[$tableName] : [],
            'nameSpace' => $nameSpace,
            'messageCategory' => $messageCategory,
        ];
    }

    public function generateRelations()
    {
        $db = $this->getDbConnection();
        $relations = [];
        $schemaNames = $this->getSchemaNames();
        foreach ($schemaNames as $schemaName) {
            foreach ($db->getSchema()->getTableSchemas($schemaName) as $originTableSchema) {
                /* @var $originTableSchema \yii\db\TableSchema */
                foreach ($originTableSchema->foreignKeys as $dbmsRelationInfo) {
                    $relatedTableName = ArrayHelper::remove($dbmsRelationInfo, 0);
                    /* @var $relatedTableSchema \yii\db\TableSchema */
                    $relatedTableSchema = $db->getTableSchema($relatedTableName);

                    if ($relatedTableSchema === null) {
                        // Foreign key could point to non-existing table: https://github.com/yiisoft/yii2-gii/issues/34
                        // skip if not mentioned in metadata
                        continue;
                    }

                    /**
                     * menentukan class & namespace
                     */
                    $originModelClass = $this->getModelClass($originTableSchema->fullName);
                    $originModelNameSpace = StringHelper::dirname($originModelClass);
                    $originModelClassName = StringHelper::basename($originModelClass);
                    $relatedModelClass = $this->getModelClass($relatedTableSchema->fullName);
                    $relatedModelNameSpace = StringHelper::dirname($relatedModelClass);
                    $relatedModelClassName = StringHelper::basename($relatedModelClass);
                    $isSameNameSpace = ($originModelNameSpace == $relatedModelNameSpace);
                    $originModelMention = $isSameNameSpace ? $originModelClassName : "\\".$originModelClass;
                    $relatedModelMention = $isSameNameSpace ? $relatedModelClassName : "\\".$relatedModelClass;

                    /**
                     * mulai generate sesuai definisi FK tabel
                     */
                    $foreignKeys = array_keys($dbmsRelationInfo);
                    $foreignKey = $foreignKeys[0];

                    /**
                     * menambahkan info-relasi dr tabel utama ke tabel relasi
                     * misal: tabel transaksi hasOne klien
                     */
                    $originalLink = $this->generateRelationLink(array_flip($dbmsRelationInfo));
                    $relationName = $this->generateRelationName($relations, $originTableSchema, $foreignKey, false);
                    $originalAlias = $this->isNeedAlias(false, $relatedTableName, $foreignKey);
                    $originalQueryCode = "return \$this->hasOne({$relatedModelMention}::class, {$originalLink})";

                    if ($originalAlias) {
                        $originalQueryCode .= "->alias(static::".strtoupper($relationName).")";
                    }

                    $originalRelation = [
                        'alias' => $originalAlias,
                        'nameSpace' => $relatedModelNameSpace,
                        'className' => $relatedModelClassName,
                        'query' => $originalQueryCode.";",
                    ];
                    $relations[$originTableSchema->fullName]['hasOne'][$relationName] = $originalRelation;

                    /**
                     * menambahkan relasi kebalikannya
                     * misal: tabel klien hasMany transaksi
                     */
                    $hasMany = $this->isHasManyRelation($originTableSchema, $foreignKeys);
                    $hasWhat = $hasMany ? 'hasMany' : 'hasOne';
                    $relatedLink = $this->generateRelationLink($dbmsRelationInfo);
                    $relatedRelationName = $this->generateRelationName($relations, $relatedTableSchema, $originModelClassName, $hasMany, $foreignKey);
                    $relatedAlias = $this->isNeedAlias($hasMany, $relatedTableName, $foreignKey);
                    $relatedQueryCode = "return \$this\n                ->".$hasWhat."({$originModelMention}::class, {$relatedLink})\n";

                    if ($relatedAlias) {
                        $relatedQueryCode .= "                ->alias(static::".strtoupper($relatedRelationName).")\n";
                    }
                    if ($hasMany && $originTableSchema->getColumn('is_deleted') !== null) {
                        $relatedQueryCode .= "                ->andFilterWhere(\$filter)\n";
                    }
                    $relatedRelation = [
                        'alias' => $relatedAlias,
                        'nameSpace' => $originModelNameSpace,
                        'className' => $originModelClassName,
                        'query' => $relatedQueryCode."        ;",
                    ];
                    $relations[$relatedTableSchema->fullName][$hasWhat][$relatedRelationName] = $relatedRelation;
                }

                if (($junctionFks = $this->checkJunctionTable($originTableSchema)) === false) {
                    continue;
                }

                $rel = $this->generateManyManyRelations($originTableSchema, $junctionFks, $relations);

                foreach ($rel as $tableName => $junctionList) {
                    foreach ($junctionList as $junctionName => $junction) {
                        $relations[$tableName]['hasJunction'][$junctionName] = $junction;
                    }
                }
            }
        }

        return $relations;
    }

    /**
     * get model class name from saved seting or generate it
     * @param string $tableName
     * @return string
     */
    public function getModelClass($tableName)
    {
        if (isset($this->modelClasses[$tableName])) {
            return $this->modelClasses[$tableName];
        }

        return $this->ns."\\".$this->generateClassName($tableName);
    }

    /**
     * Generates the attribute labels for the specified table.
     * @param \yii\db\TableSchema $table the table schema
     * @return array the generated attribute labels (name => label)
     */
    public function generateLabels($table)
    {
        $labels = [];
        foreach ($table->columns as $column) {
            if ($this->generateLabelsFromComments && !empty($column->comment)) {
                $labels[$column->name] = $column->comment;
            } elseif (!strcasecmp($column->name, 'id')) {
                $labels[$column->name] = 'ID';
            } else {
                $label = Inflector::camel2words($column->name);
                if (!empty($label) && substr_compare($label, ' id', -3, 3, true) === 0) {
                    $label = substr($label, 0, -3);
                }
                $labels[$column->name] = $label;
            }
        }

        return $labels;
    }

    public function isNeedAlias($hasMany, $refTable, $fk)
    {
        if ($hasMany) {
            return FALSE;
        }

        $tablename = strtolower($refTable);
        $key = strtolower($fk);

        if (!empty($key) && strcasecmp($key, 'id')) {
            if (substr_compare($key, 'id', -2, 2, true) === 0) {
                $key = rtrim(substr($key, 0, -2), '_');
            } elseif (substr_compare($key, 'id', 0, 2, true) === 0) {
                $key = ltrim(substr($key, 2, strlen($key)), '_');
            }
        }

        return ($tablename !== $key);
    }

    /**
     * {@inheritdoc}
     */
    public function generateRelationName($relations, $table, $key, $multiple, $fk = NULL)
    {
        if ($multiple && $fk) {
            if (strcasecmp($fk, 'id')) {
                if (substr_compare($fk, 'id', -2, 2, true) === 0) {
                    $fk = rtrim(substr($fk, 0, -2), '_');
                } elseif (substr_compare($fk, 'id', 0, 2, true) === 0) {
                    $fk = ltrim(substr($fk, 2, strlen($fk)), '_');
                }
            }

            if (strpos($table->fullName, $fk) === FALSE) {
                // shall generate something like 'BooksAsAuthor'
                return Inflector::id2camel(Inflector::pluralize($key).'_as_'.$fk, '_');
            }
        }

        // else use default generator
        return parent::generateRelationName($relations, $table, $key, $multiple);
    }

    /**
     * @inheritdoc
     */
    public function generateManyManyRelations($table, $fks, $relations)
    {
        $db = $this->getDbConnection();
        $rel = [];

        foreach ($fks as $pair) {
            list($firstKey, $secondKey) = $pair;
            $table0 = $firstKey[0];
            $table1 = $secondKey[0];
            unset($firstKey[0], $secondKey[0]);

            $table0Schema = $db->getTableSchema($table0);
            $table1Schema = $db->getTableSchema($table1);

            // @see https://github.com/yiisoft/yii2-gii/issues/166
            if ($table0Schema === null || $table1Schema === null) {
                continue;
            }

            /**
             * menentukan class & namespace
             */
            $modelClass_0 = $this->getModelClass($table0);
            $modelNameSpace_0 = StringHelper::dirname($modelClass_0);
            $modelClassName_0 = StringHelper::basename($modelClass_0);
            $modelClass_1 = $this->getModelClass($table1);
            $modelNameSpace_1 = StringHelper::dirname($modelClass_1);
            $modelClassName_1 = StringHelper::basename($modelClass_1);
            $isSameNameSpace = ($modelNameSpace_0 == $modelNameSpace_1);
            $modelMention_0 = $isSameNameSpace ? $modelClassName_0 : "\\".$modelClass_0;
            $modelMention_1 = $isSameNameSpace ? $modelClassName_1 : "\\".$modelClass_1;

            $link0 = $this->generateRelationLink(array_flip($secondKey));
            $viaLink0 = $this->generateRelationLink($firstKey);
            $relationName0 = $this->generateRelationName($relations, $table0Schema, key($secondKey), true);

            $rel[$table0Schema->fullName][$relationName0] = [
                'alias' => FALSE,
                'nameSpace' => $modelNameSpace_1,
                'className' => $modelClassName_1,
                'query' => "return \$this->hasMany($modelMention_1::class, $link0)"
                ."->viaTable('".$this->generateTableName($table->name)."', $viaLink0);",
            ];

            $link1 = $this->generateRelationLink(array_flip($firstKey));
            $viaLink1 = $this->generateRelationLink($secondKey);
            $relationName1 = $this->generateRelationName($relations, $table1Schema, key($firstKey), true);

            $rel[$table1Schema->fullName][$relationName1] = [
                'alias' => FALSE,
                'nameSpace' => $modelNameSpace_0,
                'className' => $modelClassName_0,
                'query' => "return \$this->hasMany($modelMention_0::class, $link1)->viaTable('"
                .$this->generateTableName($table->name)."', $viaLink1);",
            ];
        }

        return $rel;
    }

    /**
     * Generates validation rules for the specified table and add enum value validation.
     *
     * @param \yii\db\TableSchema $table the table schema
     *
     * @return array the generated validation rules
     */
    public function generateRules($table)
    {
        $types = [];
        $lengths = [];
        $stringcols = []; //--- added
        foreach ($table->columns as $column) {
            if ($this->isNoRule($column)) {             //--- replaced with own function
                continue;
            }
            if (!$column->allowNull && $column->defaultValue === null) {
                $types['required'][] = $column->name;
            }
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                    if (substr_compare($column->name, '_at', -3, 3, true) === 0) {
                        $types['timestamp'][] = $column->name;
                        break;
                    }
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_TINYINT:
                    $types['integer'][] = $column->name;
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $column->name;
                    break;
                case Schema::TYPE_DATE:
                    $types['date'][] = $column->name;   //--- added
                    break;                              //--- added
                case Schema::TYPE_TIME:
                    $types['safe'][] = $column->name;   //--- added
                    break;                              //--- added
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $types['datetime'][] = $column->name;   //--- added
                    break;                                  //--- added
                case Schema::TYPE_JSON:
                    $types['safe'][] = $column->name;
                    break;
                default: // strings
                    $stringcols[] = $column->name; //--- added
                    if ($column->size > 0) {
                        $lengths[$column->size][] = $column->name;
                    } else {
                        $types['string'][] = $column->name;
                    }
            }
        }

        $groups = [];     //--- added

        if ($stringcols) {     //--- added
            $plaincols = implode("', '", $stringcols);
            $groups['filter'][] = <<<TXT
[
                ['{$plaincols}'],
                \\fredyns\\stringcleaner\\yii2\\PlaintextValidator::class,
            ]
TXT;
        }   //--- added

        $rules = [];
        $driverName = $this->getDbDriverName();
        foreach ($types as $type => $columns) {
            if ($type === 'timestamp') {                                                                                 //--- added
                foreach ($columns as $columnName) {
                    $groups['format'][] = <<<RULE
[
                ['{$columnName}'],
                'date',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'timeZone' => Yii::\$app->timeZone,
                'timestampAttribute' => '{$columnName}',
                'timestampAttributeTimeZone' => 'UTC',
                'when' => function (\$model) {
                    return !is_numeric(\$model->{$columnName});
                },
            ]
RULE;
                }
                continue;                                                                                           //--- added
            }
            if ($type === 'date') {                                                                                 //--- added
                $groups['format'][] = "[['".implode("', '", $columns)."'], 'date', 'format' => 'yyyy-MM-dd']";    //--- added
                continue;                                                                                           //--- added
            }                                                                                                       //--- added
            if ($type === 'datetime') {                                                                                 //--- added
                $groups['format'][] = "[['".implode("', '", $columns)."'], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss']";    //--- added
                continue;                                                                                           //--- added
            }                                                                                                       //--- added
            if ($driverName === 'pgsql' && $type === 'integer') {
                $groups['default'][] = "[['".implode("', '", $columns)."'], 'default', 'value' => null]";
            }
            $groups['type'][] = "[['".implode("', '", $columns)."'], '$type']";
        }
        ksort($lengths);
        foreach ($lengths as $length => $columns) {
            $groups['type'][] = "[['".implode("', '", $columns)."'], 'string', 'max' => $length]";
        }

        $db = $this->getDbConnection();

        // Unique indexes rules
        try {
            $uniqueIndexes = array_merge($db->getSchema()->findUniqueIndexes($table), [$table->primaryKey]);
            $uniqueIndexes = array_unique($uniqueIndexes, SORT_REGULAR);
            foreach ($uniqueIndexes as $uniqueColumns) {
                // Avoid validating auto incremental columns
                if (!$this->isColumnAutoIncremental($table, $uniqueColumns)) {
                    $attributesCount = count($uniqueColumns);

                    if ($attributesCount === 1) {
                        $groups['restriction'][] = "[['".$uniqueColumns[0]."'], 'unique']";
                    } elseif ($attributesCount > 1) {
                        $columnsList = implode("', '", $uniqueColumns);
                        $groups['restriction'][] = "[['$columnsList'], 'unique', 'targetAttribute' => ['$columnsList']]";
                    }
                }
            }
        } catch (NotSupportedException $e) {
            // doesn't support unique indexes information...do nothing
        }

        // Exist rules for foreign keys
        foreach ($table->foreignKeys as $dbmsRelationInfo) {
            $refTable = $dbmsRelationInfo[0];
            $refTableSchema = $db->getTableSchema($refTable);
            if ($refTableSchema === null) {
                // Foreign key could point to non-existing table: https://github.com/yiisoft/yii2-gii/issues/34
                continue;
            }
            unset($dbmsRelationInfo[0]);

            /**
             * menentukan class & namespace
             */
            $originModelClass = $this->getModelClass($table->fullName);
            $originModelNameSpace = StringHelper::dirname($originModelClass);
            $originModelClassName = StringHelper::basename($originModelClass);
            $relatedModelClass = $this->getModelClass($refTable);
            $relatedModelNameSpace = StringHelper::dirname($relatedModelClass);
            $relatedModelClassName = StringHelper::basename($relatedModelClass);
            $isSameNameSpace = ($originModelNameSpace == $relatedModelNameSpace);
            $originModelMention = $isSameNameSpace ? $originModelClassName : $originModelClass;
            $relatedModelMention = $isSameNameSpace ? $relatedModelClassName : "\\".$relatedModelClass;

            $attributes = implode("', '", array_keys($dbmsRelationInfo));
            $targetAttributes = [];
            foreach ($dbmsRelationInfo as $key => $value) {
                $targetAttributes[] = "'$key' => '$value'";
            }
            $targetAttributes = implode(', ', $targetAttributes);
            $groups['constraint'][] = <<<TXT
[
                ['{$attributes}'],
                'exist',
                'skipOnError' => true,
                'targetClass' => $relatedModelMention::class,
                'targetAttribute' => [$targetAttributes],
            ]
TXT;
        }

        /**
         * convert category to rules:
         */
        $groupNames = [
            'filter',
            'default',
            'required',
            'type',
            'format',
            'restriction',
            'constraint',
            'safe',
        ];
        foreach ($groupNames as $groupName) {
            $rules[] = "# ".$groupName;

            if (isset($groups[$groupName])) {
                $rules = ArrayHelper::merge($rules, $groups[$groupName]);
            }
        }       //--- added

        return $rules;
    }

    /**
     * check whether a column skip the rule generator
     * @param ColumnSchema $column
     * @return boolean
     */
    public function isNoRule(ColumnSchema $column)
    {
        $skipedColums = [
            // keys
            'id', 'uid',
            // soft-delete
            'is_deleted', 'deleted_at', 'deleted_by',
            // blamable
            'created_by', 'updated_by',
            // timestamp
            'created_at', 'updated_at',
        ];

        return ($column->autoIncrement OR in_array($column->name, $skipedColums));
    }

    /**
     * {@inheritdoc}
     */
    public function getTableNames()
    {
        parent::getTableNames();

        if ($this->tableName == '*') {
            $skipTables = [
                // Yii tables
                'migration', 'yii_session',
                // uploaded file
                'uploaded_file',
                // user extension
                'user', 'profile', 'social_account', 'token',
                // rbac
                'auth_assignment', 'auth_item', 'auth_item_child', 'auth_rule',
                // menu table
                'menu',
            ];

            foreach ($skipTables as $skipTable) {
                $k = array_search($skipTable, $this->tableNames);
                if ($k !== FALSE) {
                    unset($this->tableNames[$k]);
                }
            }
        }

        return $this->tableNames;
    }

}