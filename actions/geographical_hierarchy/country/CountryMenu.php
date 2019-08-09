<?php

namespace app\actions\geographical_hierarchy\country;

/**
 * Action menu manager for model Country *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class CountryMenu extends \app\lib\ModelMenu
{
    public static $controller = '/geographical_hierarchy/country';
    public static $softdelete = false;

    public static function actionControls()
    {
        return [
            static::INDEX => \app\actions\geographical_hierarchy\country\index\ActionControl::class,
            static::VIEW => \app\actions\geographical_hierarchy\country\view\ActionControl::class,
            static::CREATE => \app\actions\geographical_hierarchy\country\create\ActionControl::class,
            static::UPDATE => \app\actions\geographical_hierarchy\country\update\ActionControl::class,
            static::DELETE => \app\actions\geographical_hierarchy\country\delete\ActionControl::class,
        ];
    }

}