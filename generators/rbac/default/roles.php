<?php

use yii\helpers\VarDumper;

/* @var $this yii\web\View  */
/* @var $generator app\generators\rbac\Generator  */

$content = VarDumper::export($generator->getRoles());
$content = str_replace("'type' => 1,", "'type' => Item::TYPE_ROLE,", $content);

echo <<<PHP
<?php

use yii\\rbac\\Item;

return {$content};
PHP;
