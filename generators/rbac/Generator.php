<?php

namespace app\generators\rbac;

use Yii;
use yii\base\NotSupportedException;
use yii\db\Query;
use yii\db\Schema;
use yii\db\mysql\ColumnSchema;
use yii\gii\CodeFile;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\rbac\DbManager;
use yii\rbac\Item;

class Generator extends \yii\gii\Generator
{
    /**
     * @var string|array definition for Database Auth manager
     */
    public $dbManager = DbManager::class;

    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     * After the DbManager object is created, if you want to change this property, you should only assign it
     * with a DB connection object.
     * Starting from version 2.0.2, this can also be a configuration array for creating the object.
     */
    public $db = 'db';

    /**
     * @var string the name of the table storing authorization items. Defaults to "auth_item".
     */
    public $itemTable = '{{%auth_item}}';

    /**
     * @var string the name of the table storing authorization item hierarchy. Defaults to "auth_item_child".
     */
    public $itemChildTable = '{{%auth_item_child}}';

    /**
     * @var string the name of the table storing authorization item assignments. Defaults to "auth_assignment".
     */
    public $assignmentTable = '{{%auth_assignment}}';

    /**
     * @var string the name of the table storing rules. Defaults to "auth_rule".
     */
    public $ruleTable = '{{%auth_rule}}';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'RBAC File';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'Export RBAC database to php file for PhpManager.';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
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
        return [
            // main
            'items.php', // '@app/rbac/items.php'
            'rules.php', //'@app/rbac/rules.php'
            'assignments.php', //'@app/rbac/assignments.php'
            // extra
            'routes.php', //'@app/rbac/routes.php'
            'permissions.php', //'@app/rbac/permissions.php'
            'roles.php', //'@app/rbac/roles.php'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $files = [];
        $rbac_folder = Yii::getAlias('@app').'/rbac/';
        $template_path = $this->getTemplatePath();
        foreach (scandir($template_path) as $file) {
            if (is_file($template_path.'/'.$file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile($rbac_folder.$file, $this->render($file));
            }
        }

        return $files;
    }

    /**
     * @return DbManager
     */
    public function getAuthManager()
    {
        if (empty($this->_authManager)) {
            $this->_authManager = Yii::createObject($this->dbManager);
        }

        return $this->_authManager;
    }
    private $_authManager;

    /**
     * @return \yii\db\Connection $db The database connection.
     */
    public function getDB()
    {
        return Yii::$app->get($this->db);
    }

    /**
     * @return array
     */
    public function getRules()
    {
        $query = (new Query())
            ->select(['name', 'data'])
            ->from($this->ruleTable)
            ->orderBy(['name' => SORT_ASC])
            ->indexBy('name')
        ;

        return $query->all($this->getDB());
    }

    /**
     * @param string $type
     * @param array $condition
     * @return array
     */
    public function getItems($type, $condition = null)
    {
        $query = (new Query())
            ->select([
                'name',
                'type',
                'description',
                'ruleName' => 'rule_name',
                'data',
            ])
            ->from($this->itemTable)
            ->where(['type' => $type])
            ->orderBy(['name' => SORT_ASC])
            ->indexBy('name')
        ;

        if ($condition) {
            $query->andWhere($condition);
        }

        return $query->all($this->getDB());
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->getItems(Item::TYPE_PERMISSION, ['like', 'name', '/%', false]);
    }

    /**
     * @return array
     */
    public function getPermissions()
    {
        $permissions = $this->getItems(Item::TYPE_PERMISSION, ['not like', 'name', '/%', false]);

        foreach (array_keys($permissions) as $name) {
            $children = (new Query())
                ->select(['child'])
                ->from($this->itemChildTable)
                ->where(['parent' => $name])
                ->orderBy(['child' => SORT_ASC])
                ->column($this->getDB())
            ;

            if ($children) {
                $permissions[$name]['children'] = $children;
            }
        }

        return $permissions;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $roles = $this->getItems(Item::TYPE_ROLE);

        foreach (array_keys($roles) as $name) {
            $children = (new Query())
                ->select(['child'])
                ->from($this->itemChildTable)
                ->where(['parent' => $name])
                ->orderBy(['child' => SORT_ASC])
                ->column($this->getDB())
            ;

            if ($children) {
                $roles[$name]['children'] = $children;
            }
        }

        return $roles;
    }

    /**
     * @return array
     */
    public function getAssignments()
    {
        $query = (new Query())
            ->from($this->assignmentTable)
            ->orderBy([
            'user_id' => SORT_ASC,
            'item_name' => SORT_ASC,
            ])
        ;

        $rows = $query->all($this->getDB());

        $assignments = [];
        foreach ($rows as $row) {
            $assignments[$row['user_id']][] = $row['item_name'];
        }

        return $assignments;
    }

}