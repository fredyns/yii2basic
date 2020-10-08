<?php
/**
 * this file responsible for database connection used
 *
 * sample & doc can be found at https://www.yiiframework.com/doc/guide/2.0/en/db-dao#creating-db-connections
 */
if (file_exists(__DIR__ . '/host/db.php')) {
    return require __DIR__ . '/host/db.php'; // use custom host configuration if any
}

// default DB config
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=db;port=5432;dbname=yii2basic',
    //  'dsn' => 'mysql:host=localhost;dbname=yii2basic', // sample for MySQL
    'username' => 'postgres',
    'password' => 'pg_secret',
    'charset' => 'utf8',
    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
