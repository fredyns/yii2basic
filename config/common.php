<?php
/**
 * this file responsible for common configuration that used both in console or web app
 */
$db = require __DIR__ . '/db.php';
$redis = require __DIR__ . '/redis.php';
//  $mongodb = require __DIR__ . '/mongodb.php';
$mailer = require __DIR__ . '/mailer.php';
$params = require __DIR__ . '/params.php';

return [
    'id' => 'basic_app',
    'name' => 'My Web-Application',
    'timeZone' => 'Asia/Jakarta',
    'language' => 'id-ID',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    //  uncomment these if using queue
    //  'bootstrap' => [
    //      'queue', // The component registers its own console commands
    //  ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            //  'class' => 'yii\mongodb\Cache', // sample for using mongo
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    //  'class' => 'yii\mongodb\log\MongoDbTarget', // sample for using mongo
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'redis' => $redis,
        //  'mongodb' => $mongodb,
        'mailer' => $mailer,
        //  uncomment these if using queue
        //  'queue' => [
        //      'class' => 'yii\queue\redis\Queue',
        //      'as log' => 'yii\queue\LogBehavior',
        //  ],
        //  uncomment these if using RBAC
        //  'authManager' => [
        //    'class' => 'yii\rbac\DbManager',
        //  ],
    ],
    'params' => $params,
];
