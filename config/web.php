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
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'sessionTable' => 'yii_session',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        /* // uncomment to enable RBAC
          'authManager' => [
          'class' => 'yii\rbac\DbManager'
          ],
          // */
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableRegistration' => true,
            'admins' => ['admin', 'fredy.ns'],
            'modelMap' => [
                'User' => 'app\models\User',
                'Profile' => 'app\models\Profile',
            ],
        ],
        'admin' => [
            'class' => 'mdm\admin\Module',
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module'
        // enter optional module parameters below - only if you need to  
        // use your own export download action or custom translation 
        // message source
        // 'downloadAction' => 'gridview/export/download',
        // 'i18n' => []
        ],
    ],
    /* // uncomment to configure RBAC
      'as access' => [
      'class' => 'mdm\admin\components\AccessControl',
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
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        'generators' => [
            'modelclass' => [
                'class' => \app\generators\modelclass\Generator::class,
            ],
            'gii-config' => [
                'class' => \app\generators\giiconfig\Generator::class,
            ],
            'my-model' => [
                'class' => 'app\generators\model\Generator',
            ],
            'my-crud' => [
                'class' => 'app\generators\crud\Generator',
            ],
        ],
    ];

    // uncomment when RBAC activated
    //$config['as access']['allowActions'][] = 'admin/*';
    //$config['as access']['allowActions'][] = 'gii/*';
}

return \yii\helpers\ArrayHelper::merge($common, $config);
