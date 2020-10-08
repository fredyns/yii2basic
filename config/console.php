<?php
/**
 * this file fully responsible for console app configuration
 */
$common = require __DIR__.'/common.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [],
    /*
      'controllerMap' => [
      'fixture' => [ // Fixture generation command line.
      'class' => 'yii\faker\FixtureController',
      ],
      ],
     */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return \yii\helpers\ArrayHelper::merge($common, $config);
