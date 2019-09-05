<?php

use yii\helpers\Inflector;

/* @var $this yii\web\View  */
/* @var $generator app\generators\model2\Generator  */
/* @var $tableName string  full table name */
/* @var $modelClass string  */
/* @var $ns string  */
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
        "value": "models",
        "name": "messageCategory"
    }
}