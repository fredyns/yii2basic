<?php

namespace app\actions\geographical_hierarchy\type;

/**
 * Action menu manager for model Type *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class TypeMenu extends \app\lib\ModelMenu
{
    public static $controller = '/geographical_hierarchy/type';
    public static $softdelete = false;

    public static function actionControls()
    {
        return [
            static::INDEX => \app\actions\geographical_hierarchy\type\index\ActionControl::class,
            static::VIEW => \app\actions\geographical_hierarchy\type\view\ActionControl::class,
            static::CREATE => \app\actions\geographical_hierarchy\type\create\ActionControl::class,
            static::UPDATE => \app\actions\geographical_hierarchy\type\update\ActionControl::class,
            static::DELETE => \app\actions\geographical_hierarchy\type\delete\ActionControl::class,
        ];
    }

}