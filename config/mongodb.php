<?php
/**
 * this file responsible for database connection used
 */
if (file_exists(__DIR__ . '/host/mongodb.php')) {
    return require __DIR__ . '/host/mongodb.php'; // use custom host configuration if any
}

// default mongo configuration
return [
    'class' => 'yii\mongodb\Connection',
    // override mongoUser & mongoPassword with actual authentication
    'dsn' => 'mongodb://mg_user:mg_secret@mongo:27017/yii2basic',
];
