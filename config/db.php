<?php
/**
 * this file responsible for database connection used
 */
if (file_exists(__DIR__.'/host/db.php')) {
    // use host configuration if any
    return require __DIR__.'/host/db.php';
}

return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
