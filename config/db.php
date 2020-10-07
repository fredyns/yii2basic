<?php
/**
 * this file responsible for database connection used
 *
 * sample & doc can be found at https://www.yiiframework.com/doc/guide/2.0/en/db-dao#creating-db-connections
 */
if (file_exists(__DIR__.'/host/db.php')) {
    // use host configuration if any
    return require __DIR__.'/host/db.php';
}

return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=yii2basic',
    'username' => 'fredy',
    'password' => '',
    'charset' => 'utf8',
    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
