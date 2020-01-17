<?php
/**
 * this file responsible for redis connection used
 */
if (file_exists(__DIR__.'/host/redis.php')) {
    // use host configuration if any
    return require __DIR__.'/host/redis.php';
}

return [
    'class' => \yii\redis\Connection::class,
    'hostname' => 'localhost',
    'port' => 6379,
    'database' => 0,
];
