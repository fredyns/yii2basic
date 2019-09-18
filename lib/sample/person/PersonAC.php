<?php

namespace app\lib\sample\person;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * control which action is executable by user
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class PersonAC extends \app\components\ActionControl
{

    public static function canIndex()
    {
        return true;
    }

    public static function canView()
    {
        return true;
    }

    public static function canCreate()
    {
        return true;
    }

    public static function canUpdate()
    {
        return true;
    }

    public static function canDelete()
    {
        return static::isLoggedIn();
    }

    public static function canRestore()
    {
        return static::isAdmin();
    }

    public static function canListArchive()
    {
        return static::isAdmin();
    }

    public static function canListDeleted()
    {
        return static::isAdmin();
    }

}