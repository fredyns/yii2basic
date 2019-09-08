<?php

namespace app\actions\sample\person;

/**
 * Action menu manager for model Person *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class PersonMenu extends \app\components\ModelMenu
{
    public static $controller = '/sample/person';
    public static $softdelete = true;

    public static function actionControls()
    {
        return [
            static::INDEX => \app\actions\sample\person\index\ActionControl::class,
            static::VIEW => \app\actions\sample\person\view\ActionControl::class,
            static::CREATE => \app\actions\sample\person\create\ActionControl::class,
            static::UPDATE => \app\actions\sample\person\update\ActionControl::class,
            static::DELETE => \app\actions\sample\person\delete\ActionControl::class,
            static::DELETED => \app\actions\sample\person\deleted\ActionControl::class,
            static::RESTORE => \app\actions\sample\person\restore\ActionControl::class,
            static::ARCHIVE => \app\actions\sample\person\archive\ActionControl::class,
        ];
    }

}