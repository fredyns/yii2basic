<?= "<?php\n" ?>
return \yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/routes.php'
    , require __DIR__ . '/permissions.php'
    , require __DIR__ . '/roles.php'
);