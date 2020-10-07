<?php
/**
 * this file responsible for common configuration that used both in console or web app
 */
$db = require __DIR__ . '/db.php';
$mailer = require __DIR__ . '/mailer.php';
$params = require __DIR__ . '/params.php';
//  $mongodb = require __DIR__.'/mongodb.php';
//  $redis = require __DIR__.'/redis.php';

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
            'class' => \yii\caching\FileCache::class,
            //  'class' => \yii\mongodb\Cache::class,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    //  'class' => \yii\mongodb\log\MongoDbTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        //  'redis' => $redis,  // uncomment if using redis
        //  'mongodb' => $mongodb,
        'mailer' => $mailer,
        //  uncomment these if using queue
        //  'queue' => [
        //      'class' => \yii\queue\redis\Queue::class,
        //      'as log' => \yii\queue\LogBehavior::class,
        //  ],
        //  'authManager' => [
        //    'class' => \yii\rbac\PhpManager::class,
        //  ],
        //  'mdmAuthManager' => [
        //    'class' => \yii\rbac\DbManager::class,
        //  ],
    ],
    'params' => $params,
];
