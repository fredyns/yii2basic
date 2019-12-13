<?php

use yii\helpers\VarDumper;
use yii\rbac\DbManager;
use yii\rbac\Item;

/* @var $this yii\web\View  */
/* @var $generator app\generators\rbac\Generator  */

$content = VarDumper::export($generator->getRules());
echo <<<PHP
<?php

use yii\\rbac\\Item;

return {$content};
PHP;
