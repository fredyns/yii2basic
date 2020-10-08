<?php
/**
 * this file responsible for app parameters
 */
if (file_exists(__DIR__.'/host/params.php')) {
    return require __DIR__.'/host/params.php'; // use custom host configuration if any
}

// default params
return [
    'adminEmail' => 'email@fredyns.net',
    'senderEmail' => 'email@fredyns.net',
    'senderName' => 'Fredy mailer',
];
