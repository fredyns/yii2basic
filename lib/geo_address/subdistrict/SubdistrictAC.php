<?php

namespace app\lib\geo_address\subdistrict;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * control which action is executable by user
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class SubdistrictAC extends \app\components\ActionControl
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

}