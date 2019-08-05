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
    public static $dropdownSeparator = '<li role="presentation" class="divider"></li>';

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
     * default link options
     * @param string $action
     * @return array
     */
    public static function linkOptionsDefaultFor($action)
    {
        return [
            'title' => static::labelFor($action),
            'aria-label' => static::labelFor($action),
            'data-pjax' => '0',
        ];
    }

    /**
     * list of link options for each actions available
     * @return array
     */
    public static function linkOptions()
    {
        return [];
    }

    /**
     * get closure/function for button generator
     * @param string$action
     * @return \closure
     */
    public static function linkOptionsFor($action)
    {
        $linkOptions_list = static::linkOptions();

        if (isset($linkOptions_list[$action])) {
            return $linkOptions_list[$action];
        }

        return static::linkOptionsDefaultFor($action);
    }

    /**
     * create default closure/function for button generator
     * @param string $action
     * @return \Closure
     */
    public static function buttonDefaultFor($action)
    {
        return function ($url, $model = null, $key = null) use ($action) {
            $label = static::iconFor($action).' '.static::labelFor($action);
            $options = static::linkOptionsFor($action);
            return Html::a($label, $url, $options);
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
            $options = static::linkOptionsFor($action);
            return '<li>'.Html::a($label, $url, $options).'</li>';
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

        return static::dropdownButtonDefaultFor($action);
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

    /**
     * 
     * @param string $actions
     * @param ActiveRecord $model
     * @return string the rendering result of the widget.
     */
    public static function renderButtons($actions, ActiveRecord $model = null)
    {
        $html = '\n';
        $actions = (array) $actions;
        foreach ($actions as $action) {
            // ensure mentioned action
            if (is_string($action) == FALSE) {
                continue;
            }

            // action control callback
            $control_callback = static::visibleButtonFor($action);
            if ($control_callback) {
                $visible = call_user_func($control_callback, $model);
                if ($visible == FALSE) {
                    continue;
                }
            }

            // button generator
            $generator = static::buttonFor($action);
            if (empty($generator) OR is_callable($generator) === FALSE) {
                continue;
            }

            // url
            $url = static::createUrlFor($action, $model);

            // render button
            $html .= call_user_func($generator, $url, $model).'\n';
        }

        return $html;
    }

    /**
     * 
     * @param string $actions
     * @param ActiveRecord $model
     * @return string the rendering result of the widget.
     */
    public static function renderDropdown($actions, ActiveRecord $model = null)
    {
        $items = [];
        $actions = (array) $actions;
        foreach ($actions as $action) {
            // ensure mentioned action
            if (is_string($action) == FALSE) {
                continue;
            }

            // action control callback
            $control_callback = static::visibleButtonFor($action);
            if ($control_callback) {
                $visible = call_user_func($control_callback, $model);
                if ($visible == FALSE) {
                    continue;
                }
            }

            // add button
            $items[] = [
                'label' => static::iconFor($action).' '.static::labelFor($action),
                'url' => static::createUrlFor($action, $model),
            ];
        }

        if (empty($items)) {
            return NULL;
        }

        return \yii\bootstrap\Dropdown::widget([
                'items' => $items,
        ]);
    }

}