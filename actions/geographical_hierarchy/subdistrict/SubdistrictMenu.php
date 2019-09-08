<?php

namespace app\actions\geographical_hierarchy\subdistrict;

/**
 * Action menu manager for model Subdistrict *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class SubdistrictMenu extends \app\components\ModelMenu
{
    public static $controller = '/geographical_hierarchy/subdistrict';
    public static $softdelete = false;

    public static function actionControls()
    {
        return [
            static::INDEX => \app\actions\geographical_hierarchy\subdistrict\index\ActionControl::class,
            static::VIEW => \app\actions\geographical_hierarchy\subdistrict\view\ActionControl::class,
            static::CREATE => \app\actions\geographical_hierarchy\subdistrict\create\ActionControl::class,
            static::UPDATE => \app\actions\geographical_hierarchy\subdistrict\update\ActionControl::class,
            static::DELETE => \app\actions\geographical_hierarchy\subdistrict\delete\ActionControl::class,
        ];
    }

}