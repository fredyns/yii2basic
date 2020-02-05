<?php

use yii\helpers\VarDumper;
use app\models\User;

/* @var $this yii\web\View  */
/* @var $generator app\generators\rbac\Generator  */

$content = VarDumper::export($generator->getAssignments());
echo <<<PHP
<?php
/**
 * Warning!
 * user ID could be different between development & production
 */
return [
PHP;
foreach ($generator->getAssignments() as $user_id => $roles) {
    $user = User::findOne($user_id);
    if ($user) {
        echo <<<PHP

    /**
     * username : {$user->username}
     * email    : {$user->email}
     */
PHP;
    } else {
        echo <<<PHP

    /**
     * user not found
     */
PHP;
    }
    $role_dumps = VarDumper::export($roles); // export variables
    $role_dumps = str_replace("\n", "\n    ", $role_dumps); // add 4 space indent
    echo <<<PHP

    {$user_id} => {$role_dumps},
PHP;
}

echo <<<PHP

];

PHP;
