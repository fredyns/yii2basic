<?php

use yii\helpers\Inflector;

/* @var $this yii\web\View  */
/* @var $generator app\generators\model2\Generator  */
/* @var $tableName string  full table name */
/* @var $modelClass string  */
/* @var $ns string model's namespace */
/**
 * namespace pattern:
 * app/models/sub
 * app/modules/moduleId/models/sub
 */
$nameSpaceArray = explode("\\", str_replace("\\\\", "\\", $ns));
if (isset($nameSpaceArray[1]) && $nameSpaceArray[1] == 'modules' && isset($nameSpaceArray[2])) {
    $moduleId = $nameSpaceArray[2];
    $subPath = isset($nameSpaceArray[4]) ? $nameSpaceArray[4] : null;
} else {
    $moduleId = 'app';
    $subPath = isset($nameSpaceArray[2]) ? $nameSpaceArray[2] : null;
}

$messageCategory = trim("{$moduleId}/{$subPath}/models", "/");
?>
{
    "tablename": {
        "value": "<?= $tableName ?>",
        "name": "tableName"
    },
    "tableprefix": {
        "value": "",
        "name": "tablePrefix"
    },
    "modelclass": {
        "value": "<?= $modelClass ?>",
        "name": "modelClass"
    },
    "ns": {
        "value": "<?= $ns ?>",
        "name": "ns"
    },
    "baseclass": {
        "value": "yii\\db\\ActiveRecord",
        "name": "baseClass"
    },
    "db": {
        "value": "db",
        "name": "db"
    },
    "generaterelations": {
        "value": "all",
        "name": "generateRelations"
    },
    "messagecategory": {
        "value": "<?= $messageCategory ?>",
        "name": "messageCategory"
    }
}