<?php

namespace app\generators\crud;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use schmunk42\giiant\helpers\SaveForm;
use yii\db\Schema;

/**
 * This generator generates an extended version of Giiant-CRUDs.
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class Generator extends \schmunk42\giiant\generators\crud\Generator
{
    /**
     * @var string default view path
     */
    public $viewPath = '@app/views';

    /**
     * @var bool whether to overwrite extended controller classes
     */
    public $overwriteControllerClass = true;

    /**
     * @var bool whether to overwrite rest/api controller classes
     */
    public $overwriteRestControllerClass = true;

    /**
     * @var bool whether to overwrite search classes
     */
    public $overwriteSearchModelClass = true;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'My CRUD';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return "This application's CRUD generator.";
    }

    public function generate()
    {
        $accessDefinitions = require $this->getTemplatePath().'/access_definition.php';

        $this->controllerNs = StringHelper::dirname(ltrim($this->controllerClass, '\\'));
        $this->moduleNs = StringHelper::dirname(ltrim($this->controllerNs, '\\'));
        $controllerName = substr(StringHelper::basename($this->controllerClass), 0, -10);

        if ($this->singularEntities) {
            $this->modelClass = Inflector::singularize($this->modelClass);
            $this->controllerClass = Inflector::singularize(
                    substr($this->controllerClass, 0, strlen($this->controllerClass) - 10)
                ).'Controller';
            $this->searchModelClass = Inflector::singularize($this->searchModelClass);
        }

        $params['controllerClassName'] = StringHelper::basename($this->controllerClass);

        // Controller

        $controllerFile = Yii::getAlias('@'.str_replace('\\', '/', ltrim($this->controllerClass, '\\')).'.php');

        if ($this->overwriteControllerClass || !is_file($controllerFile)) {
            $files[] = new CodeFile($controllerFile, $this->render('controller.php', $params));
        }

        // API

        $restControllerFile = str_replace('controllers', 'controllers/api', StringHelper::dirname($controllerFile)).'/'.StringHelper::basename($controllerFile);

        if ($this->overwriteRestControllerClass || !is_file($restControllerFile)) {
            $files[] = new CodeFile($restControllerFile, $this->render('controller-rest.php', $params));
        }

        // migration

        $migrationDir = StringHelper::dirname(StringHelper::dirname($controllerFile)).'/migrations';

        if (file_exists($migrationDir) && $migrationDirFiles = glob($migrationDir.'/m*_'.$controllerName.'00_access.php')) {
            $this->migrationClass = pathinfo($migrationDirFiles[0], PATHINFO_FILENAME);
        } else {
            $this->migrationClass = 'm'.date('ymd_Hi').'00_'.$controllerName.'_access';
        }

        // search model

        if (!empty($this->searchModelClass)) {
            $searchModel = Yii::getAlias('@'.str_replace('\\', '/', ltrim($this->searchModelClass, '\\').'.php'));
            if ($this->overwriteSearchModelClass || !is_file($searchModel)) {
                $files[] = new CodeFile($searchModel, $this->render('search_model.php'));
            }
        }

        // views

        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath().'/views';

        foreach (scandir($templatePath) as $file) {
            if (empty($this->searchModelClass) && $file === '_search.php') {
                continue;
            }
            if (is_file($templatePath.'/'.$file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
            }
        }

        if ($this->generateAccessFilterMigrations) {

            /*
             * access migration
             */
            $migrationFile = $migrationDir.'/'.$this->migrationClass.'.php';
            $files[] = new CodeFile($migrationFile, $this->render('migration_access.php', ['accessDefinitions' => $accessDefinitions]));

            /*
             * access roles translation
             */
            $forRoleTranslationFile = StringHelper::dirname(StringHelper::dirname($controllerFile))
                .'/messages/for-translation/'
                .$controllerName.'.php';
            $files[] = new CodeFile($forRoleTranslationFile, $this->render('roles_translation.php', ['accessDefinitions' => $accessDefinitions]));
        }

        /*
         * create gii/[name]GiantCRUD.json with actual form data
         */
        $suffix = str_replace(' ', '', $this->getName());
        $controllerFileinfo = pathinfo($controllerFile);
        $formDataFile = '/gii/'.str_replace('Controller', $suffix, $controllerFileinfo['filename']).'.json';
        //$formData = json_encode($this->getFormAttributesValues());
        $formData = json_encode(SaveForm::getFormAttributesValues($this, $this->formAttributes()), JSON_PRETTY_PRINT);
        $files[] = new CodeFile($formDataFile, $formData);

        return $files;
    }

    public function generateSearchRules()
    {
        if (($table = $this->getTableSchema()) === false) {
            return ["[['".implode("', '", $this->getColumnNames())."'], 'safe']"];
        }

        $types = [];
        $skipCols = ['created_at', 'updated_at', 'is_deleted', 'deleted_at'];
        foreach ($table->columns as $column) {
            if (in_array($column->name, $skipCols) OR substr_compare($column->name, 'at', -2, 2, true) === 0) {
                // skip saveral + timestamp columns
                continue;
            }
            switch ($column->type) {
                case Schema::TYPE_TINYINT:
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
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
                    $types['date'][] = $column->name;
                case Schema::TYPE_TIME:
                    $types['safe'][] = $column->name;
                    break;
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $types['datetime'][] = $column->name;
                    break;
                default:
                    $types['safe'][] = $column->name;
                    break;
            }
        }

        $rules = [];
        foreach ($types as $type => $columns) {
            switch ($type) {
                case 'date':     //--- added
                    $rules[] = "[['".implode("', '", $columns)."'], 'date', 'format' => 'yyyy-MM-dd']";
                    break;
                case 'datetime':
                    $rules[] = "[['".implode("', '", $columns)."'], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss']";    //--- added
                    break;
                case 'safe':
                    $column_list = implode("', '", $columns);
                    $rules[] = <<<TXT
[
                ['{$column_list}'],
                \\fredyns\\stringcleaner\\yii2\\PlaintextValidator::class,
            ]
TXT;
                    break;
                default:
                    $rules[] = "[['".implode("', '", $columns)."'], '$type']";
                    break;
            }
        }

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function generateSearchConditions()
    {
        $columns = [];
        if (($table = $this->getTableSchema()) === false) {
            $class = $this->modelClass;
            /* @var $model \yii\base\Model */
            $model = new $class();
            foreach ($model->attributes() as $attribute) {
                $columns[$attribute] = 'unknown';
            }
        } else {
            foreach ($table->columns as $column) {
                // skip timestamp filter
                if (strpos($column->name, '_at') === FALSE) {
                    $columns[$column->name] = $column->type;
                }
            }
        }

        $likeConditions = [];
        $hashConditions = [];
        foreach ($columns as $column => $type) {
            switch ($type) {
                case Schema::TYPE_TINYINT:
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_BOOLEAN:
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $hashConditions[] = "static::tableName().'.{$column}' => \$this->{$column},";
                    break;
                default:
                    $likeKeyword = $this->getClassDbDriverName() === 'pgsql' ? 'ilike' : 'like';
                    $likeConditions[] = "->andFilterWhere(['{$likeKeyword}', static::tableName().'.{$column}', \$this->{$column}])";
                    break;
            }
        }

        $conditions = [];
        if (!empty($hashConditions)) {
            $conditions[] = "\$query->andFilterWhere([\n"
                .str_repeat(' ', 12).implode("\n".str_repeat(' ', 12), $hashConditions)
                ."\n".str_repeat(' ', 8)."]);\n";
        }
        if (!empty($likeConditions)) {
            $conditions[] = "\$query\n"
                .str_repeat(' ', 12).implode("\n".str_repeat(' ', 12), $likeConditions)."\n"
                .str_repeat(' ', 8).";\n";
        }

        return $conditions;
    }

    /**
     * @return array List of providers. Keys and values contain the same strings
     */
    public function generateProviderCheckboxListData()
    {
        $files = FileHelper::findFiles(
                __DIR__.DIRECTORY_SEPARATOR.'providers', [
                'only' => ['*.php'],
                'recursive' => false,
                ]
        );

        foreach ($files as $file) {
            require_once $file;
        }

        $providers = array_filter(
            get_declared_classes(), function ($a) {
            return stripos($a, __NAMESPACE__.'\providers') !== false;
            }
        );

        return array_combine($providers, $providers);
    }

}