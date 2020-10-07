<?php
/**
 * this file responsible for database connection used
 *
 * sample & doc can be found at https://www.yiiframework.com/doc/guide/2.0/en/db-dao#creating-db-connections
 */
$costum_db_host = str_replace('//', '/', __DIR__ . '/host/db.php');
if (file_exists($costum_db_host)) {
    // use host configuration if any
    return require $costum_db_host;
}

return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=yii2basic',
    //  'dsn' => 'mysql:host=localhost;dbname=yii2basic', // sample for MySQL
    'username' => 'fredy',
    'password' => '',
    'charset' => 'utf8',
    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
