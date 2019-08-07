<?php

namespace app\actions\sample\book;

/**
 * Action menu manager for model Book *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class BookMenu extends \app\lib\ModelMenu
{
    public static $controller = '/sample/book';
    public static $softdelete = true;

    public static function actionControls()
    {
        return [
            static::INDEX => \app\actions\sample\book\index\ActionControl::class,
            static::VIEW => \app\actions\sample\book\view\ActionControl::class,
            static::CREATE => \app\actions\sample\book\create\ActionControl::class,
            static::UPDATE => \app\actions\sample\book\update\ActionControl::class,
            static::DELETE => \app\actions\sample\book\delete\ActionControl::class,
            static::DELETED => \app\actions\sample\book\deleted\ActionControl::class,
            static::RESTORE => \app\actions\sample\book\restore\ActionControl::class,
            static::ARCHIVE => \app\actions\sample\book\archive\ActionControl::class,
        ];
    }

}