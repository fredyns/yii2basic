<?php
/**
 * this file fully responsible for web app configuration
 */
$common = require __DIR__.'/common.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'zrJcHeL_E6us_zBGrmaa-kOd0F69FAyQ',
            'parsers' => [
                'application/json' => \yii\web\JsonParser::class,
            ],
        ],
        'session' => [
            'class' => \yii\web\DbSession::class,
            'sessionTable' => 'yii_session',
        ],
        'user' => [
            'identityClass' => \app\models\User::class,
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
    ],
    'modules' => [
        'user' => [
            'class' => \dektrium\user\Module::class,
            'enableRegistration' => true,
            'admins' => ['admin', 'fredy.ns'],
            'modelMap' => [
                'User' => \app\models\User::class,
                'Profile' => \app\models\Profile::class,
            ],
        ],
        'admin' => [
            'class' => \mdm\admin\Module::class,
        ],
        'gridview' => [
            'class' => \kartik\grid\Module::class
        // enter optional module parameters below - only if you need to  
        // use your own export download action or custom translation 
        // message source
        // 'downloadAction' => 'gridview/export/download',
        // 'i18n' => []
        ],
    ],
    /* // uncomment to configure RBAC
      'as access' => [
      'class' => \mdm\admin\components\AccessControl::class,
      'allowActions' => [
      // The actions listed here will be allowed to everyone including guests.
      // So, 'admin/*' should not appear here in the production, of course.
      // But in the earlier stages of your development, you may probably want to
      // add a lot of actions here until you finally completed setting up rbac,
      // otherwise you may not even take a first step.
      'site/*',
      ]
      ],
      // */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        //
        //  uncomment these to ad queue to debug panel
        //  'panels' => [
        //      'queue' => \yii\queue\debug\Panel::class,
        //  ],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        'generators' => [
            'tableclass' => [
                'class' => \app\generators\tableclass\Generator::class,
            ],
            'my-model' => [
                'class' => \app\generators\model\Generator::class,
            ],
            'my-crud' => [
                'class' => \app\generators\crud\Generator::class,
            ],
            'rbac' => [
                'class' => \app\generators\rbac\Generator::class,
            ],
        ],
    ];

    // uncomment when RBAC activated
    //$config['as access']['allowActions'][] = 'admin/*';
    //$config['as access']['allowActions'][] = 'gii/*';
}

return \yii\helpers\ArrayHelper::merge($common, $config);
