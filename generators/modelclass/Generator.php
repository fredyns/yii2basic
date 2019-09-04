<?php

namespace app\generators\modelclass;

use Yii;
use yii\base\NotSupportedException;
use yii\db\mysql\ColumnSchema;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class Generator extends \yii\gii\generators\model\Generator
{
    public $tableName = '*';
    public $modelClasses = [];

    /**
     * filepath location
     * @return string
     */
    public static function getFilePath()
    {
        return Yii::getAlias('@app').'/gii/model_namespaces.json';
    }

    /**
     * read saved/preconfigured model namespace map
     * configuration file as JSON format
     *  {
     *      'table': "namespace"
     *  }
     */
    public static function readData()
    {
        $filepath = static::getFilePath();

        if (file_exists($filepath)) {
            $content = file_get_contents($filepath);
            return (array) json_decode($content, true);
        }

        return [];
    }

    public function init()
    {
        parent::init();

        // read saved data
        $this->modelClasses = static::readData();

        // default
        foreach ($this->getTableNames() as $tableName) {
            if (isset($this->modelClasses[$tableName])) {
                continue;
            }
            $this->modelClasses[$tableName] = $this->ns."\\".$this->generateClassName($tableName);
        }

        // ordering
        ksort($this->modelClasses);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Model Classes';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'This generates model class colection as refference for model\'s relation & rules.';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['modelClasses', 'each', 'rule' => ['string']],
        ];
    }

    /**
     * all form fields for saving in saved forms.
     *
     * @return array
     */
    public function formAttributes()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function requiredTemplates()
    {
        return ['formdata.php'];
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $files = [
            new CodeFile($this->getFilePath(), json_encode($this->modelClasses, JSON_PRETTY_PRINT))
        ];

        foreach ($this->modelClasses as $tableName => $modelClass) {
            $params = [
                'tableName' => $tableName,
                'modelClass' => StringHelper::basename($modelClass),
                'ns' => str_replace("\\", "\\\\", StringHelper::dirname($modelClass)),
            ];
            $suffix = 'MyModel2'; // sesuai generator yg mau dipake
            $formDataFile = Yii::getAlias('@app').'/gii/'.$tableName.$suffix.'.json';
            $files[] = new CodeFile(
                $formDataFile, $this->render('formdata.php', $params)
            );

        }

        return $files;
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