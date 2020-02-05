<?= "<?php\n" ?>
/**
 * Auth items consist of 3 types: routes, permission & roles.
 * each configured in separated files.
 */
return \yii\helpers\ArrayHelper::merge(
        require __DIR__.'/routes.php'
        , require __DIR__.'/permissions.php'
        , require __DIR__.'/roles.php'
);