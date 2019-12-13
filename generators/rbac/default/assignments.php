<?php

use yii\helpers\VarDumper;
use yii\rbac\Item;

/* @var $this yii\web\View  */
/* @var $generator app\generators\rbac\Generator  */

$content = VarDumper::export($generator->getAssignments());
echo <<<PHP
<?php

use yii\\rbac\\Item;

return {$content};
PHP;
