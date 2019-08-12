<?php

namespace app\actions\geographical_hierarchy\region;

/**
 * Action menu manager for model Region *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class RegionMenu extends \app\lib\ModelMenu
{
    public static $controller = '/geographical_hierarchy/region';
    public static $softdelete = false;

    public static function actionControls()
    {
        return [
            static::INDEX => \app\actions\geographical_hierarchy\region\index\ActionControl::class,
            static::VIEW => \app\actions\geographical_hierarchy\region\view\ActionControl::class,
            static::CREATE => \app\actions\geographical_hierarchy\region\create\ActionControl::class,
            static::UPDATE => \app\actions\geographical_hierarchy\region\update\ActionControl::class,
            static::DELETE => \app\actions\geographical_hierarchy\region\delete\ActionControl::class,
        ];
    }

}