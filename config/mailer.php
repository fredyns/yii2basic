<?php
/**
 * this file responsible for mailer service
 */
if (file_exists(__DIR__.'/host/mailer.php')) {
    return require __DIR__.'/host/mailer.php'; // use custom host configuration if any
}

// default mailer config
return [
    'class' => 'yii\swiftmailer\Mailer',
    // send all mails to a file by default. You have to set
    // 'useFileTransport' to false and configure a transport
    // for the mailer to send real emails.
    'useFileTransport' => true,
];
