<?php
/* @var $this yii\web\View  */
/* @var $generator app\generators\rbac\Generator  */

echo <<<PHP
<?php
return [
PHP;

foreach ($generator->getRules() as $key => $row) {
    $data = $row['data'];
    if (!is_resource($data)) {
        continue;
    }
    $serialized_data = stream_get_contents($data);
    $item = unserialize($serialized_data);
    if (!is_object($item)) {
        continue;
    }
    $classname = get_class($item);
    echo <<<PHP

    '{$key}' => serialize(new \\{$classname}),
PHP;
}

echo <<<PHP

];

PHP;
