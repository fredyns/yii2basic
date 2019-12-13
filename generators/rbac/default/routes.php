<?php

use yii\helpers\VarDumper;
use yii\rbac\Item;

/* @var $this yii\web\View  */
/* @var $generator app\generators\rbac\Generator  */

$content = VarDumper::export($generator->getRoutes());
$content = str_replace("'type' => '2',", "'type' => Item::TYPE_PERMISSION,", $content);

echo <<<PHP
<?php

use yii\\rbac\\Item;

return {$content};
PHP;
