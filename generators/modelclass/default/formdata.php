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
    "generatelabelsfromcomments": {
        "value": "0",
        "name": "generateLabelsFromComments"
    },
    "generatehintsfromcomments": {
        "value": "0",
        "name": "generateHintsFromComments"
    },
    "generatemodelclass": {
        "value": "1",
        "name": "generateModelClass"
    },
    "generatequery": {
        "value": "0",
        "name": "generateQuery"
    },
    "queryns": {
        "value": "<?= $ns ?>",
        "name": "queryNs"
    },
    "queryclass": {
        "value": "",
        "name": "queryClass"
    },
    "querybaseclass": {
        "value": "yii\\db\\ActiveQuery",
        "name": "queryBaseClass"
    },
    "enablei18n": {
        "value": "1",
        "name": "enableI18N"
    },
    "singularentities": {
        "value": "0",
        "name": "singularEntities"
    },
    "messagecategory": {
        "value": "models",
        "name": "messageCategory"
    },
    "usetranslatablebehavior": {
        "value": "0",
        "name": "useTranslatableBehavior"
    },
    "languagetablename": {
        "value": "{{table}}_lang",
        "name": "languageTableName"
    },
    "languagecodecolumn": {
        "value": "language",
        "name": "languageCodeColumn"
    },
    "useblameablebehavior": {
        "value": "1",
        "name": "useBlameableBehavior"
    },
    "createdbycolumn": {
        "value": "created_by",
        "name": "createdByColumn"
    },
    "updatedbycolumn": {
        "value": "updated_by",
        "name": "updatedByColumn"
    },
    "usetimestampbehavior": {
        "value": "1",
        "name": "useTimestampBehavior"
    },
    "createdatcolumn": {
        "value": "created_at",
        "name": "createdAtColumn"
    },
    "updatedatcolumn": {
        "value": "updated_at",
        "name": "updatedAtColumn"
    }
}