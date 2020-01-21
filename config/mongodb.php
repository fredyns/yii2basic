<?php
/**
 * this file responsible for database connection used
 */
if (file_exists(__DIR__.'/host/mongodb.php')) {
    // use host configuration if any
    return require __DIR__.'/host/mongodb.php';
}

return [
    'class' => \yii\mongodb\Connection::class,
    // overide mongoUser & mongoPassword with actual authentication
    'dsn' => 'mongodb://mongoUser:mongoPassword@localhost:27017/vgm-certification',
];
