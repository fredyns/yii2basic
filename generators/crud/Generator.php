<?php

namespace app\generators\crud;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\ArrayHelper;
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
        /**
         * compose params
         */
        $params = $this->generateParams();

        /**
         * define actions to generate
         */
        $action_list = ['index', 'create', 'view', 'update', 'delete'];

        if ($this->getTableSchema()->getColumn('is_deleted')) {
            $action_list = ArrayHelper::merge($action_list, ['restore', 'deleted', 'archive']);
        }

        /**
         * enumerate each actions
         */
        foreach ($action_list as $action) {
            /**
             * generate access control for current action
             */
            $files[] = $this->generateAccessControl($action, $params);

            /**
             * generate action class for current action
             */
            $files[] = $this->generateActiveAction($action, $params);
        }

        /**
         * generate search
         */
        $files[] = $this->generateSearch($params);

        /**
         * generate controller
         */
        $files[] = $this->generateController($params);

        /**
         * generate REST API
         */
        $files[] = $this->generateRestAPI($params);

        /**
         * generate view
         */
        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath().'/views';
        foreach (scandir($templatePath) as $file) {
            if (empty($this->searchModelClass) && $file === '_search.php') {
                continue;
            }
            if (is_file($templatePath.'/'.$file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file", $params));
            }
        }

        /**
         * save form data
         */
        $files[] = $this->generateFormData($params);

        // result
        return $files;
    }

    /**
     * generate params to render codefile
     * @return array
     */
    public function generateParams()
    {
        // prepare params
        $controllerClassName = StringHelper::basename($this->controllerClass);
        $controllerNameSpace = StringHelper::dirname(ltrim($this->controllerClass, '\\'));
        $moduleNameSpace = StringHelper::dirname(ltrim($controllerNameSpace, '\\'));
        $actionParent = [
            'index' => 'IndexAction',
            'create' => 'CreateAction',
            'view' => 'ViewAction',
            'update' => 'UpdateAction',
            'delete' => 'DeleteAction',
            'restore' => 'RestoreAction',
            'deleted' => 'IndexAction',
            'archive' => 'IndexAction',
        ];
        $actionParentNameSpace = str_replace('controllers', 'actions', $controllerNameSpace)
            .'\\'.Inflector::camel2id(str_replace('Controller', '', $controllerClassName), '_');

        /**
         * range search params
         */
        $dateRange = [];
        $timestampRange = [];
        $skipCols = ['updated_at', 'deleted_at'];
        foreach ($this->getTableSchema()->columns as $column) {
            if (in_array($column->name, $skipCols)) {
                continue;
            }
            if ($column->type == Schema::TYPE_INTEGER && substr_compare($column->name, '_at', -3, 3, true) === 0) {
                $filterName = substr($column->name, 0, (strlen($column->name) - 3));
                if (!$filterName) {
                    continue;
                }
                $timestampRange[$filterName] = $column->name;
                continue;
            }
            if ($column->type == Schema::TYPE_DATE) {
                if (substr_compare($column->name, '_date', -5, 5, true) === 0) {
                    $filterName = substr($column->name, 0, (strlen($column->name) - 5));
                } else {
                    $filterName = $column->name;
                }
                if (!$filterName) {
                    continue;
                }
                $dateRange[$filterName] = $column->name;
                continue;
            }
        }

        /**
         * sync vars
         */
        $this->controllerNs = $controllerNameSpace;
        $this->moduleNs = $moduleNameSpace;

        /**
         * compose params as array
         */
        return compact(
            'controllerClassName'
            , 'controllerNameSpace'
            , 'moduleNameSpace'
            , 'actionParent'
            , 'actionParentNameSpace'
            , 'dateRange'
            , 'timestampRange'
        );
    }

    /**
     * generate & create path
     * @param string $namespaced_path
     * @return string
     */
    public function generatePath($namespaced_path)
    {
        $namespace_separator = "\\";
        $namespaced_path = ltrim($namespaced_path, $namespace_separator);

        if (DIRECTORY_SEPARATOR != $namespace_separator) {
            $namespaced_path = str_replace($namespace_separator, DIRECTORY_SEPARATOR, $namespaced_path);
        }

        $path = Yii::getAlias('@app')
            .str_replace('app'.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $namespaced_path);

        $directory = pathinfo($path, PATHINFO_EXTENSION) ? StringHelper::dirname($path) : $path;
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        return $path;
    }

    /**
     * generate access control for particular action
     * @param array $params
     * @return CodeFile
     */
    public function generateAccessControl($action, $params)
    {
        $params['action'] = $action;
        $params['actionNameSpace'] = $params['actionParentNameSpace'].'\\'.$action;
        $file = $this->generatePath($params['actionNameSpace'].'/AccessControl.php');

        return new CodeFile($file, $this->render('AccessControl.php', $params));
    }

    /**
     * generate action class for particular action
     * @param array $params
     * @return CodeFile
     */
    public function generateActiveAction($action, $params)
    {
        $params['action'] = $action;
        $params['actionNameSpace'] = $params['actionParentNameSpace'].'\\'.$action;
        $file = $this->generatePath($params['actionNameSpace'].'/ActiveAction.php');

        return new CodeFile($file, $this->render('ActiveAction.php', $params));
    }

    public function generateSearch($params)
    {
        $file = $this->generatePath($this->searchModelClass.'.php');
        return new CodeFile($file, $this->render('SearchModel.php', $params));
    }

    /**
     * generate controller class
     * @param array $params
     * @return CodeFile
     */
    public function generateController($params)
    {
        $file = $this->generatePath($this->controllerClass.'.php');
        return new CodeFile($file, $this->render('controller.php', $params));
    }

    public function generateRestAPI($params)
    {
        $controller_directory = DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR;
        $rest_directory = DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR;
        $file = $this->generatePath($this->controllerClass.'.php');
        $file = str_replace($controller_directory, $rest_directory, $file);
        return new CodeFile($file, $this->render('RestAPI.php', $params));
    }

    public function generateFormData($params)
    {
        $suffix = str_replace(' ', '', $this->getName());
        $file = Yii::getAlias('@app').'/gii/'
            .str_replace('Controller', $suffix, $params['controllerClassName']).'.json';
        $content = json_encode(SaveForm::getFormAttributesValues($this, $this->formAttributes()), JSON_PRETTY_PRINT);

        return new CodeFile($file, $content);
    }

    public function generateSearchRules()
    {
        if (($table = $this->getTableSchema()) === false) {
            return ["[['".implode("', '", $this->getColumnNames())."'], 'safe']"];
        }

        $types = [];
        $skipCols = ['updated_at', 'is_deleted', 'deleted_at'];
        foreach ($table->columns as $column) {
            if ($column->name === 'created_at') {
                $types['safe'][] = $column->name;
                continue;
            }
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