<?php

namespace app\actions\geographical_hierarchy\city;

/**
 * Action menu manager for model City *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class CityMenu extends \app\components\ModelMenu
{
    public static $controller = '/geographical_hierarchy/city';
    public static $softdelete = false;

    public static function actionControls()
    {
        return [
            static::INDEX => \app\actions\geographical_hierarchy\city\index\ActionControl::class,
            static::VIEW => \app\actions\geographical_hierarchy\city\view\ActionControl::class,
            static::CREATE => \app\actions\geographical_hierarchy\city\create\ActionControl::class,
            static::UPDATE => \app\actions\geographical_hierarchy\city\update\ActionControl::class,
            static::DELETE => \app\actions\geographical_hierarchy\city\delete\ActionControl::class,
        ];
    }

}