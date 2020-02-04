<?php

use yii\helpers\VarDumper;

/* @var $this yii\web\View  */
/* @var $generator app\generators\rbac\Generator  */

$map = [];
foreach ($generator->getRules() as $key => $row) {
    $data = $row['data'];
    if (is_resource($data)) {
        $map[$key] = stream_get_contents($data);
    }
}

$content = VarDumper::export($map);
echo <<<PHP
<?php

return {$content};
PHP;
