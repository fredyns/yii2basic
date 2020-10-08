<?php
/**
 * this file responsible for redis connection used
 */
if (file_exists(__DIR__ . '/host/redis.php')) {
    return require __DIR__ . '/host/redis.php'; // use custom host configuration if any
}

// default redis config
return [
    'class' => 'yii\redis\Connection',
    'hostname' => 'Redis',
    'port' => 6379,
    'database' => 0,
];
