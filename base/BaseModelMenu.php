<?php

namespace app\base;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\lib\ActionControl;

/**
 * Description of ModelMenu
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class BaseModelMenu extends \yii\base\Component
{
    public static $controller = '';
    public static $softdelete = false;

    /**
     * get all action available
     * @return string[]
     */
    public static function actions()
    {
        return [];
    }

    /**
     * list all available URI
     * @return string[]
     */
    public static function uris()
    {
        return [];
    }

    /**
     * generate url for particular action
     * @param string $action
     * @param ActiveRecord $model
     * @param array $params additional parameters
     * @return string the generated URL
     */
    public static function createUrlFor($action, ActiveRecord $model = NULL, $params = [])
    {
        $uri = ArrayHelper::getValue(static::uris(), $action);
        if (empty($uri)) {
            return NULL;
        }

        $url = [$uri];

        if ($model) {
            foreach ($model->primaryKey() as $field) {
                $url[$field] = $model->getAttribute($field);
            }
        }

        return Url::toRoute($url);
    }

    /**
     * list label for all actions
     * @return string[]
     */
    public static function labels()
    {
        return [];
    }

    /**
     * get label for particular action
     * @return string
     */
    public static function labelFor($action)
    {
        $labels = static::labels();
        if (isset($labels[$action])) {
            return $labels[$action];
        }

        return \yii\helpers\Inflector::humanize($action, TRUE);
    }

    /**
     * list icons for all actions
     * @return string[]
     */
    public static function icons()
    {
        return [];
    }

    /**
     * get icon for particular action
     * @return string[]
     */
    public static function iconFor($action)
    {
        return ArrayHelper::getValue(static::icons(), $action, '<span class="glyphicon glyphicon-modal-window"></span>');
    }

    /**
     * list access control for all available actions
     * @return string[]
     */
    public static function actionControls()
    {
        return [];
    }

    /**
     * get access control for particular action
     * @return string[]
     */
    public static function actionControlFor($action)
    {
        return ArrayHelper::getValue(static::actionControls(), $action);
    }

    /**
     * create default closure/function for button generator
     * @param string $action
     * @return \Closure
     */
    public static function buttonDefaultFor($action)
    {
        $called_class = get_called_class();
        return function ($url, $model = null, $key = null) use ($called_class, $action) {
            $options = [
                'title' => $called_class::labelFor($action),
                'aria-label' => $called_class::labelFor($action),
                'data-pjax' => '0',
            ];
            return Html::a($called_class::iconFor($action), $url, $options);
        };
    }

    /**
     * default button generator for each actions available
     * @return array
     */
    public static function buttons()
    {
        return [];
    }

    /**
     * get closure/function for button generator
     * @param string$action
     * @return \closure
     */
    public static function buttonFor($action)
    {
        $buttons = static::buttons();

        if (isset($buttons[$action])) {
            return $buttons[$action];
        }

        return static::buttonDefaultFor($action);
    }

    /**
     * create default closure/function for dropdown button generator
     * @param string $action
     * @return \Closure
     */
    public static function dropdownButtonDefaultFor($action)
    {
        $called_class = get_called_class();
        return function ($url, $model = null, $key = null) use ($called_class, $action) {
            $label = $called_class::iconFor($action).' '.$called_class::labelFor($action);
            $options = [
                'title' => $called_class::labelFor($action),
                'aria-label' => $called_class::labelFor($action),
                'data-pjax' => '0',
            ];
            return Html::a($label, $url, $options);
        };
    }

    /**
     * default dropdown button generator for each actions available
     * @return array
     */
    public static function dropdownButtons()
    {
        return [
            'divider' => function ($url = NULL, $model = null, $key = null) {
                return '<li class="divider"></li>';
            },
        ];
    }

    /**
     * get closure/function for dropdown button generator
     * @param string$action
     * @return \closure
     */
    public static function dropdownButtonFor($action)
    {
        $buttons = static::dropdownButtons();

        if (isset($buttons[$action])) {
            return $buttons[$action];
        }

        return static::DropdownButtonDefaultFor($action);
    }

    /**
     * create default closure function to check button visibility
     * @param string $action
     * @return \closure
     */
    public static function visibleButtonDefaultFor($action)
    {
        $called_class = get_called_class();
        return function ($model, $key = null, $index = null) use ($called_class, $action) {
            $action_control_class = $called_class::actionControlFor($action);

            if ($action_control_class && class_exists($action_control_class)) {
                return ActionControl::check([
                        'class' => $action_control_class,
                        'model' => $model,
                ]);
            }

            return TRUE;
        };
    }

    /**
     * list of button visibility checker
     * @return \Closure[]
     */
    public static function visibleButtons()
    {
        return [];
    }

    /**
     * get button visibility checker for particular action
     * @param type $action
     * @return \Closure
     */
    public static function visibleButtonFor($action)
    {
        $visibility = static::visibleButtons();

        if (isset($visibility[$action])) {
            return $visibility[$action];
        }

        return static::visibleButtonDefaultFor($action);
    }

}