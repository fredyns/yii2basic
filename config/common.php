<?php
/**
 * this file responsible for common configuration that used both in console or web app
 */
$db = require __DIR__.'/db.php';
$mailer = require __DIR__.'/mailer.php';
$params = require __DIR__.'/params.php';

$config = [
    'id' => 'basic_app',
    'name' => 'My Web-Application',
    'timeZone' => 'Asia/Jakarta',
    'language' => 'id-ID',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'mailer' => $mailer,
        'authManager' => [
            'class' => \yii\rbac\PhpManager::class,
        ],
        'mdmAuthManager' => [
            'class' => \yii\rbac\DbManager::class,
        ],
    ],
    'params' => $params,
];

return $config;
