<?php

namespace app\generator\modelmeta;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class Generator extends \yii\gii\generators\model\Generator
{
    public $enableI18N = true;
    public $messageCategory = 'models';
    public $tablePrefix = null;
    public $tableName = '*';
    public $nameSpaces = [];
    public $generateHintsFromComments;
    public $singularEntities;
    public $metadata = [];

    /**
     * filepath location
     * @return string
     */
    public static function getFilePath()
    {
        return Yii::getAlias('@app/models').'/_metadata.json';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Model Meta';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'This generator generates metadata from database schema for generating models.';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(
            parent::rules(), [
            ['enableI18N', 'default', 'value' => TRUE],
            ['messageCategory', 'default', 'value' => 'models'],
            [
                [
                    'generateHintsFromComments',
                    'singularEntities',
                ],
                'boolean',
            ],
            [['tablePrefix'], 'safe'],
            ['nameSpaces', 'each', 'rule' => ['safe']],
            ]
        );
    }

    /**
     * all form fields for saving in saved forms.
     *
     * @return array
     */
    public function formAttributes()
    {
        return [
            'tablePrefix',
            'ns',
            'baseClass',
            'db',
            'generateRelations',
            'generateLabelsFromComments',
            'generateHintsFromComments',
            'generateQuery',
            'enableI18N',
            'singularEntities',
            'messageCategory',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(), [
            'generateHintsFromComments' => 'Generate Hints from DB Comments',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function hints()
    {
        return array_merge(
            parent::hints(), [
            'tablePrefix' => 'Custom table prefix, eg <code>app_</code>.<br/><b>Note!</b> overrides <code>yii\db\Connection</code> prefix!',
            'generateHintsFromComments' => 'This indicates whether the generator should generate attribute hints by using the comments of the corresponding DB columns.',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function requiredTemplates()
    {
        return [];
    }

    /**
     * read saved/preconfigured model namespace map
     * configuration file as JSON format
     *  {
     *      'table': "namespace"
     *  }
     */
    public function readMetadata()
    {
        $filepath = static::getFilePath();

        if (file_exists($filepath)) {
            $content = file_get_contents($filepath);
            return (array) json_decode($content, true);
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        // only 1 file generated
        return [
            $this->generateMetadata(),
        ];
    }

    public function generateMetadata()
    {
        // read saved data
        $this->metadata = $this->readMetadata();

        // generate modelname & namespace
        $this->generateMetaBasic();

        // generate relation regarding namespace (previously specified)
        $this->generateRelations();

        // generate file
        $filepath = $this->getFilePath();
        $content = json_encode($this->metadata, JSON_PRETTY_PRINT);

        return new CodeFile($filepath, $content);
    }

    /**
     * generate basic model class information
     */
    public function generateMetaBasic()
    {
        $db = $this->getDbConnection();

        foreach ($this->getTableNames() as $tableName) {
            $className = $this->generateClassName($tableName);
            $tableSchema = $db->getTableSchema($tableName);
            $this->metadata[$tableName] = [
                'db' => $this->db,
                'nameSpace' => ArrayHelper::getValue($this->nameSpaces, $tableName, $this->ns),
                'baseClass' => $this->baseClass,
                'className' => $className,
                'generateLabels' => $this->generateLabels($tableSchema),
                'generateHints' => $this->generateHints($tableSchema),
                'enableI18N' => $this->enableI18N,
                'messageCategory' => $this->messageCategory,
            ];
        }

        ksort($this->metadata);
    }

    /**
     * generate rules for each models, regarding generated namespace
     */
    public function generateMetaRules()
    {
        $db = $this->getDbConnection();

        foreach (array_keys($this->metadata) as $tableName) {
            $tableSchema = $db->getTableSchema($tableName);
            $this->metadata[$tableName]['rules'] = $this->generateRules($tableSchema);
        }
    }

    /**
     * @inheritdoc
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

    /**
     * Generates a class name from the specified table name.
     *
     * @param string $tableName the table name (which may contain schema prefix)
     *
     * @return string the generated class name
     */
    public function generateClassName($tableName, $useSchemaName = null)
    {
        if (isset($this->metadata[$tableName])) {
            return $this->metadata[$tableName]['className'];
        }

        if (($pos = strrpos($tableName, '.')) !== false) {
            $tableName = substr($tableName, $pos + 1);
        }

        $db = $this->getDbConnection();
        $patterns = [];
        $patterns[] = "/^{$this->tablePrefix}(.*?)$/";
        $patterns[] = "/^(.*?){$this->tablePrefix}$/";
        $patterns[] = "/^{$db->tablePrefix}(.*?)$/";
        $patterns[] = "/^(.*?){$db->tablePrefix}$/";

        if (strpos($this->tableName, '*') !== false) {
            $pattern = $this->tableName;
            if (($pos = strrpos($pattern, '.')) !== false) {
                $pattern = substr($pattern, $pos + 1);
            }
            $patterns[] = '/^'.str_replace('*', '(\w+)', $pattern).'$/';
        }

        $className = $tableName;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $tableName, $matches)) {
                $className = $matches[1];
                Yii::trace("Mapping '{$tableName}' to '{$className}' from pattern '{$pattern}'.", __METHOD__);
                break;
            }
        }

        $returnName = Inflector::id2camel($className, '_');
        if ($this->singularEntities) {
            $returnName = Inflector::singularize($returnName);
        }

        Yii::trace("Converted '{$tableName}' to '{$returnName}'.", __METHOD__);

        return $returnName;
    }

    /**
     * Generates the attribute hints for the specified table.
     *
     * @param \yii\db\TableSchema $table the table schema
     *
     * @return array the generated attribute hints (name => hint)
     *               or an empty array if $this->generateHintsFromComments is false
     */
    public function generateHints($table)
    {
        $hints = [];

        if ($this->generateHintsFromComments) {
            foreach ($table->columns as $column) {
                if (!empty($column->comment)) {
                    $hints[$column->name] = $column->comment;
                }
            }
        }

        return $hints;
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

    public function generateRelations()
    {
        $db = $this->getDbConnection();
        $relations = [];
        $schemaNames = $this->getSchemaNames();
        foreach ($schemaNames as $schemaName) {
            foreach ($db->getSchema()->getTableSchemas($schemaName) as $tableSchema) {
                /* @var $tableSchema \yii\db\TableSchema */
                $metadata = ArrayHelper::getValue($this->metadata, $tableSchema->fullName);

                if (empty($metadata)) {
                    // skip if not mentioned in metadata
                    continue;
                }

                foreach ($tableSchema->foreignKeys as $refs) {
                    $refTable = ArrayHelper::remove($refs, 0);
                    /* @var $refTableSchema \yii\db\TableSchema */
                    $refTableSchema = $db->getTableSchema($refTable);
                    $reffMetadata = ArrayHelper::getValue($this->metadata, $refTableSchema->fullName);

                    if ($refTableSchema === null OR empty($reffMetadata)) {
                        // Foreign key could point to non-existing table: https://github.com/yiisoft/yii2-gii/issues/34
                        // skip if not mentioned in metadata
                        continue;
                    }

                    /**
                     * mulai generate sesuai definisi FK tabel
                     */
                    $fks = array_keys($refs);
                    $fk = $fks[0];
                    $className = $metadata['className'];
                    $refClassName = $reffMetadata['className'];

                    if ($metadata['nameSpace'] !== $reffMetadata['nameSpace']) {
                        // add prefix if referenced model namespace is different than origin model class namespace
                        $className = '\\'.$metadata['nameSpace'].'\\'.$className;
                        $refClassName = '\\'.$reffMetadata['nameSpace'].'\\'.$refClassName;
                    }

                    // Add relation for this table
                    // tabel transaksi hasOne klien
                    $link = $this->generateRelationLink(array_flip($refs));
                    $relationName = $this->generateRelationName($relations, $tableSchema, $fk, false);
                    $alias = $this->isNeedAlias(false, $refTable, $fk);
                    $code = "return \$this->hasOne($refClassName::class, $link)";
                    if ($alias) {
                        // adding relation alias if necessary
                        $code .= "->alias(static::".strtoupper($relationName).")";
                    }
                    $relation = [
                        'code' => $code.";",
                        'class' => $reffMetadata['className'],
                        'alias' => $alias,
                    ];
                    $relations[$tableSchema->fullName][$relationName] = $relation;
                    $this->metadata[$tableSchema->fullName]['hasOne'][$relationName] = $relation;

                    // Add relation for the referenced table
                    // tabel klien hasMany transaksi
                    $hasMany = $this->isHasManyRelation($tableSchema, $fks);
                    $hasWhat = $hasMany ? 'hasMany' : 'hasOne';
                    $refLink = $this->generateRelationLink($refs);
                    $refRelationName = $this->generateRelationName($relations, $refTableSchema, $metadata['className'], $hasMany, $fk);
                    $refAlias = $this->isNeedAlias($hasMany, $refTable, $fk);
                    $refCode = "return \$this->".$hasWhat."($className::class, $refLink)";
                    if ($refAlias) {
                        $refCode .= "->alias(static::".strtoupper($refRelationName).")";
                    }
                    $refRelation = [
                        'code' => $refCode.";",
                        'class' => $metadata['className'],
                        'alias' => $refAlias,
                    ];
                    $relations[$refTableSchema->fullName][$refRelationName] = $relation;
                    $this->metadata[$refTableSchema->fullName][$hasWhat][$refRelationName] = $refRelation;
                }

                if (($junctionFks = $this->checkJunctionTable($tableSchema)) === false) {
                    continue;
                }

                $rel = $this->generateManyManyRelations($tableSchema, $junctionFks, $relations);

                foreach ($rel as $tableName => $junctionList) {
                    foreach ($junctionList as $junctionName => $junction) {
                        $this->metadata[$tableName]['hasJunction'][$junctionName] = $junction;
                    }
                }
            }
        }

        //if ($this->generateRelations === self::RELATIONS_ALL_INVERSE) {
        //    return $this->addInverseRelations($relations);
        //}

        return $relations;
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
            $metadata0 = ArrayHelper::getValue($this->metadata, $table0);
            $metadata1 = ArrayHelper::getValue($this->metadata, $table1);

            if (empty($metadata0) OR empty($metadata1)) {
                continue;
            }

            $className0 = $metadata0['className'];
            $className1 = $metadata1['className'];
            $table0Schema = $db->getTableSchema($table0);
            $table1Schema = $db->getTableSchema($table1);

            // @see https://github.com/yiisoft/yii2-gii/issues/166
            if ($table0Schema === null || $table1Schema === null) {
                continue;
            }

            if ($metadata0['nameSpace'] !== $metadata1['nameSpace']) {
                // add prefix if referenced model namespace is different than origin model class namespace
                $className0 = '\\'.$metadata0['nameSpace'].'\\'.$className0;
                $className1 = '\\'.$metadata1['nameSpace'].'\\'.$className1;
            }

            $link0 = $this->generateRelationLink(array_flip($secondKey));
            $viaLink0 = $this->generateRelationLink($firstKey);
            $relationName0 = $this->generateRelationName($relations, $table0Schema, key($secondKey), true);

            $rel[$table0Schema->fullName][$relationName0] = [
                'code' => "return \$this->hasMany($className1::class, $link0)"
                ."->viaTable('".$this->generateTableName($table->name)."', $viaLink0);",
                'class' => $metadata1['className'],
                'alias' => FALSE,
            ];

            $link1 = $this->generateRelationLink(array_flip($firstKey));
            $viaLink1 = $this->generateRelationLink($secondKey);
            $relationName1 = $this->generateRelationName($relations, $table1Schema, key($firstKey), true);

            $rel[$table1Schema->fullName][$relationName1] = [
                'code' => "return \$this->hasMany($className0::class, $link1)->viaTable('"
                .$this->generateTableName($table->name)."', $viaLink1);",
                'class' => $metadata0['className'],
                'alias' => FALSE,
            ];
        }

        return $rel;
    }

    /**
     * prepare ENUM field values.
     *
     * @param array $columns
     *
     * @return array
     */
    public function getEnum($columns)
    {
        $enum = [];
        foreach ($columns as $column) {
            if (!$this->isEnum($column)) {
                continue;
            }

            $column_camel_name = str_replace(' ', '', ucwords(implode(' ', explode('_', $column->name))));
            $enum[$column->name]['func_opts_name'] = 'opts'.$column_camel_name;
            $enum[$column->name]['func_get_label_name'] = 'get'.$column_camel_name.'ValueLabel';
            $enum[$column->name]['values'] = [];

            $enum_values = explode(',', substr($column->dbType, 4, strlen($column->dbType) - 1));

            foreach ($enum_values as $value) {
                $value = trim($value, "()'");

                $const_name = strtoupper($column->name.'_'.$value);
                $const_name = preg_replace('/\s+/', '_', $const_name);
                $const_name = str_replace(['-', '_', ' '], '_', $const_name);
                $const_name = preg_replace('/[^A-Z0-9_]/', '', $const_name);

                $label = Inflector::camel2words($value);

                $enum[$column->name]['values'][] = [
                    'value' => $value,
                    'const_name' => $const_name,
                    'label' => $label,
                ];
            }
        }

        return $enum;
    }

    /**
     * validate is ENUM.
     *
     * @param  $column table column
     *
     * @return type
     */
    public function isEnum($column)
    {
        return substr(strtoupper($column->dbType), 0, 4) == 'ENUM';
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

        $category = [];     //--- added

        if ($stringcols) {     //--- added
            $plaincols = implode("', '", $stringcols);
            $category['filter'][] = <<<TXT
[
                ['{$plaincols}'],
                \\fredyns\\stringcleaner\\yii2\\PlaintextValidator::class,
            ]
TXT;
        }   //--- added

        $rules = [];
        $driverName = $this->getDbDriverName();
        foreach ($types as $type => $columns) {
            if ($type === 'date') {                                                                                 //--- added
                $category['format'][] = "[['".implode("', '", $columns)."'], 'date', 'format' => 'yyyy-MM-dd']";    //--- added
                continue;                                                                                           //--- added
            }                                                                                                       //--- added
            if ($type === 'datetime') {                                                                                 //--- added
                $category['format'][] = "[['".implode("', '", $columns)."'], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss']";    //--- added
                continue;                                                                                           //--- added
            }                                                                                                       //--- added
            if ($driverName === 'pgsql' && $type === 'integer') {
                $category['default'][] = "[['".implode("', '", $columns)."'], 'default', 'value' => null]";
            }
            $category['type'][] = "[['".implode("', '", $columns)."'], '$type']";
        }
        ksort($lengths);
        foreach ($lengths as $length => $columns) {
            $category['type'][] = "[['".implode("', '", $columns)."'], 'string', 'max' => $length]";
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
                        $category['restriction'][] = "[['".$uniqueColumns[0]."'], 'unique']";
                    } elseif ($attributesCount > 1) {
                        $columnsList = implode("', '", $uniqueColumns);
                        $category['restriction'][] = "[['$columnsList'], 'unique', 'targetAttribute' => ['$columnsList']]";
                    }
                }
            }
        } catch (NotSupportedException $e) {
            // doesn't support unique indexes information...do nothing
        }

        // Exist rules for foreign keys
        foreach ($table->foreignKeys as $refs) {
            $refTable = $refs[0];
            $refTableSchema = $db->getTableSchema($refTable);
            if ($refTableSchema === null OR isset($this->metadata[$refTable])) {
                // Foreign key could point to non-existing table: https://github.com/yiisoft/yii2-gii/issues/34
                continue;
            }
            unset($refs[0]);
            $refNameSpace = $this->metadata[$refTable]['nameSpace'];
            $refClassName = $this->metadata[$refTable]['className'];
            $attributes = implode("', '", array_keys($refs));
            $targetAttributes = [];
            foreach ($refs as $key => $value) {
                $targetAttributes[] = "'$key' => '$value'";
            }
            $targetAttributes = implode(', ', $targetAttributes);
            $category['constraint'][] = <<<TXT
[
                ['{$attributes}'],
                'exist',
                'skipOnError' => true,
                'targetClass' => \\$refNameSpace\\$refClassName::class,
                'targetAttribute' => [$targetAttributes],
            ]
TXT;
        }

        /**
         * convert category to rules:
         */
        $caties = [
            'filter',
            'default',
            'required',
            'type',
            'format',
            'restriction',
            'constraint',
            'safe',
        ];
        foreach ($caties as $cat) {
            $rules[] = "# ".$cat;

            if (isset($category[$cat])) {
                $rules = ArrayHelper::merge($rules, $category[$cat]);
            }
        }       //--- added

        return $rules;
    }

    /**
     * @return \yii\db\Connection the DB connection from the DI container or as application component specified by [[db]]
     */
    public function getDbConnection()
    {
        if (Yii::$container->has($this->db)) {
            return Yii::$container->get($this->db);
        } else {
            return Yii::$app->get($this->db);
        }
    }

    /**
     * Validates the [[db]] attribute.
     */
    public function validateDb()
    {
        if (Yii::$container->has($this->db)) {
            return true;
        } else {
            return parent::validateDb();
        }
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