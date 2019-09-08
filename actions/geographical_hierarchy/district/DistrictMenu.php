<?php

namespace app\actions\geographical_hierarchy\district;

/**
 * Action menu manager for model District *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class DistrictMenu extends \app\components\ModelMenu
{
    public static $controller = '/geographical_hierarchy/district';
    public static $softdelete = false;

    public static function actionControls()
    {
        return [
            static::INDEX => \app\actions\geographical_hierarchy\district\index\ActionControl::class,
            static::VIEW => \app\actions\geographical_hierarchy\district\view\ActionControl::class,
            static::CREATE => \app\actions\geographical_hierarchy\district\create\ActionControl::class,
            static::UPDATE => \app\actions\geographical_hierarchy\district\update\ActionControl::class,
            static::DELETE => \app\actions\geographical_hierarchy\district\delete\ActionControl::class,
        ];
    }

}