<?php
/**
 * check host domain
 */
$current_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'vgm.bki.co.id';
$allowed_hosts = [
    'yii2basic.fredyns.net',
    'yii2basic.test',
    'localhost',
];

if (in_array($current_host, $allowed_hosts) == false) {
    die('Application error.');
}


/**
 * strip weird uri that similiar to base url
 */
$weird_uri = 'http://'.$current_host;
$_SERVER['REQUEST_URI'] = str_replace($weird_uri, '', $_SERVER['REQUEST_URI']);


/*
 * strip exessive slash suffix
 */
$_SERVER['REQUEST_URI'] = rtrim($_SERVER['REQUEST_URI'], '/');

/*
 * strip exessive slash infix
 */
while (strpos($_SERVER['REQUEST_URI'], '//') !== FALSE) {
    $_SERVER['REQUEST_URI'] = str_replace('//', '/', $_SERVER['REQUEST_URI']);
}


/*
 * deny suspicious URI pattern
 */
$suspicious_uris = [
    // anything that don;t belong to app
    '.php',// accessing php files
    '.jsp',// accessing java files
    '.htm',// accessing html files
    '/.',// looking for hidden files
    '/wp-',// looking wordpress files
    'blog',// looking for blog uri
    'icon-',// we don't have apple icon here
    // beware of similiar slug name or asset files
];
foreach ($suspicious_uris as $suspicious_uri) {
    if (strpos($_SERVER['REQUEST_URI'], $suspicious_uri) !== FALSE) {
        die('');
    }
}
