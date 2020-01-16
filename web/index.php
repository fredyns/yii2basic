<?php
/**
 * lock domain
 */
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$allowed_hosts = [
    // add your domain here
    'yii2basic.test',
    'localhost',
];

if (in_array($host, $allowed_hosts) == false) {
    die('Application error.');
}

/*
 * deny suspicious URI pattern
 */
$suspicious_uris = [
    '.php',// accessing php files
    '.jsp',// accessing java files
    '.htm',// accessing html files
    '/.',// looking for hidden files
    '/wp-',// looking wordpress files
    'blog',// looking for blog uri
    '/apple-touch-icon-',// we don't have apple icon here
    // beware of similiar slug name or asset files
];
foreach ($suspicious_uris as $suspicious_uri) {
    if (strpos($_SERVER['REQUEST_URI'], $suspicious_uri) !== FALSE) {
        die('');
    }
}

/*
 * fix redundant slash
 */
if (isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = rtrim($_SERVER['REQUEST_URI'], '/');
    while (strpos($_SERVER['REQUEST_URI'], '//') !== FALSE) {
        $_SERVER['REQUEST_URI'] = str_replace('//', '/', $_SERVER['REQUEST_URI']);
    }
}

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
