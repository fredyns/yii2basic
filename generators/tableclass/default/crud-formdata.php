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
    $searchModelClass = trim("app\\\\modules\\\\{$moduleId}\\\\lib\\\\{$subPath}", "\\")."\\\\".$modelClass."Search";
    $controllerClass = trim("app\\\\modules\\\\{$moduleId}\\\\controllers\\\\{$subPath}", "\\")."\\\\".$modelClass."Controller";
    $viewPath = "@app\/modules\/{$moduleId}\/views".($subPath ? "\/".$subPath : '');
} else {
    $moduleId = 'app';
    $subPath = isset($nameSpaceArray[2]) ? $nameSpaceArray[2] : null;
    $searchModelClass = trim("app\\\\lib\\\\{$subPath}", "\\")."\\\\".$modelClass."Search";
    $controllerClass = trim("app\\\\controllers\\\\{$subPath}", "\\")."\\\\".$modelClass."Controller";
    $viewPath = "@app\/views".($subPath ? "\/".$subPath : '');
}

$modelMessageCategory = trim("{$moduleId}/{$subPath}", "/")."/models";
?>
{
    "modelclass": {
        "value": "<?= $ns ?>\\<?= $modelClass ?>",
        "name": "modelClass"
    },
    "searchmodelclass": {
        "value": "<?= $searchModelClass ?>",
        "name": "searchModelClass"
    },
    "controllerclass": {
        "value": "<?= $controllerClass ?>",
        "name": "controllerClass"
    },
    "basecontrollerclass": {
        "value": "yii\\web\\Controller",
        "name": "baseControllerClass"
    },
    "viewpath": {
        "value": "<?= $viewPath ?>",
        "name": "viewPath"
    },
    "indexwidgettype": {
        "value": "grid",
        "name": "indexWidgetType"
    },
    "formlayout": {
        "value": "horizontal",
        "name": "formLayout"
    },
    "providerlist": {
        "value": [
            "app\\generators\\crud\\providers\\CallbackProvider",
            "app\\generators\\crud\\providers\\DateProvider",
            "app\\generators\\crud\\providers\\RelationProvider",
            "app\\generators\\crud\\providers\\TimestampProvider"
        ],
        "name": "providerList"
    },
    "template": {
        "value": "default",
        "name": "template"
    },
    "modelmessagecategory": {
        "value": "<?= str_replace("/","\\/",$modelMessageCategory) ?>",
        "name": "modelMessageCategory"
    }
}
