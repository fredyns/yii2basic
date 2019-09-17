<?php

namespace app\components;

use Yii;
use yii\base\UserException;

/**
 * simple action control
 * contain function to check whether particular action is runnable by user
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class ActionControl
{
    public static $error = [];
    public static $catchError = FALSE;

    /**
     * check whether particular action is executable
     * @param type $action
     * @param type $params
     * @return boolean
     */
    public static function can($action, $params = [])
    {
        $function = 'can'.Inflector::camelize($action);

        if (method_exists(static::class, $function)) {
            return call_user_func_array([static::class, $function], $params);
        }

        return static::addError(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * ready to catch any error message
     */
    public static function catchError()
    {
        static::resetError();
        static::$catchError = TRUE;
    }

    /**
     * ignore any error message
     */
    public static function suppressError()
    {
        static::$catchError = FALSE;
    }

    /**
     * get all error message as joined string
     * @param string $glue
     * @return string
     */
    public static function getErrorText($glue = "\n")
    {
        return implode($glue, static::$error);
    }

    /**
     * get error message as exception object
     * @param string $glue
     * @return UserException
     */
    public static function exception($glue = "\n", $reset = true)
    {
        $errorText = static::$error ? static::getErrorText($glue) : Yii::t('messages', "Unknown error.");

        if ($reset) {
            static::resetError();
        }

        return new UserException($errorText);
    }

    /**
     * put error messages to session
     * @param string $category
     */
    public static function flashMessages($category = 'error', $reset = true)
    {
        foreach (static::$error as $msg) {
            Yii::$app->getSession()->addFlash($category, $msg);
        }

        if ($reset) {
            static::resetError();
        }
    }

    /**
     * reset error message handling
     */
    public static function resetError()
    {
        static::$error = [];
        static::suppressError();
    }

    /**
     * put error message & return false as result
     * @param type $message
     * @return boolean
     */
    public static function addError($message)
    {
        if (static::$catchError) {
            static::$error[] = $message;
        }

        return FALSE;
    }

    /**
     * check whether user is logged-in
     * @return boolean
     */
    public static function isLoggedIn()
    {
        if (Yii::$app->user->isGuest) {
            return static::addError(Yii::t('messages', "Please login first."));
        }

        return true;
    }

    /**
     * check whether user is system administrator
     * @return boolean
     */
    public static function isAdmin()
    {
        if (!static::isLoggedIn()) {
            return FALSE;
        }

        if (Yii::$app->user->identity->isAdmin == FALSE) {
            return static::addError(Yii::t('messages', "Page forbidden."));
        }

        return true;
    }

}