<?php

namespace app\generator\model;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ColumnSchema;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class Generator extends \yii\gii\Generator
{
    public $enableI18N = true;
    public $messageCategory = 'models';

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
        return "This application's model generator.";
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
        $metadata = \app\generator\modelmeta\Generator::readMetadata();
        $files = [];

        foreach ($metadata as $tableName => $params) {
            $this->enableI18N = $params['enableI18N'];
            $this->messageCategory = $params['messageCategory'];
            $db = $this->getDbConnection($params['db']);
            $tableSchema = $db->getTableSchema($tableName);
            $params['tableSchema'] = $tableSchema;
            $baseModelFile = Yii::getAlias('@'.str_replace('\\', '/', $params['nameSpace'])).'/'.$params['className'].'.php';
            $files[] = new CodeFile($baseModelFile, $this->render('model.php', $params));
        }

        return $files;
    }

    /**
     * @return \yii\db\Connection the DB connection from the DI container or as application component specified by [[db]]
     */
    public function getDbConnection($db)
    {
        if (Yii::$container->has($db)) {
            return Yii::$container->get($db);
        } else {
            return Yii::$app->get($db);
        }
    }

}