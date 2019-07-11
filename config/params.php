<?php
/**
 * this file responsible for app parameters
 */
if (file_exists(__DIR__.'/host/params.php')) {
    // use host configuration if any
    return require __DIR__.'/host/params.php';
}

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
];
