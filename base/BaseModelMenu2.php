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
class BaseModelMenu2 extends \yii\base\Component
{
    public static $controller = '';
    public static $softdelete = false;
    public static $dropdownSeparator = '<li role="presentation" class="divider"></li>';

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
        $uri = ArrayHelper::getValue(static::uris(), $action, static::$controller.'/'.$action);
        $url = [$uri];

        if ($model) {
            foreach ($model->primaryKey() as $field) {
                $url[$field] = $model->getAttribute($field);
            }
        }

        return Url::toRoute($url);
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
        // find configured class
        $class = ArrayHelper::getValue(static::actionControls(), $action);
        if ($class) {
            return $class;
        }

        // lookup based on uri
        $controller_subnamespace = str_replace("/", "\\", trim(static::$controller, "/"));
        $alternative_class = "app\\actions\\{$controller_subnamespace}\\{$action}";
        if (class_exists($alternative_class)) {
            return $alternative_class;
        }

        // no access controll class
        return NULL;
    }

    /**
     * create default closure/function for button generator
     * @param string $action
     * @return \Closure
     */
    public static function buttonDefaultFor($action)
    {
        return function ($url, $model = null, $key = null) use ($action) {
            $text = Yii::t('cruds', ucfirst($action));
            $label = '<span class="glyphicon glyphicon-new-window"></span> '.$text;
            $options = [
                'title' => $text,
                'aria-label' => $text,
                'data-pjax' => FALSE,
            ];
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
     * create default closure/function for tool generator
     * @param string $action
     * @return \Closure
     */
    public static function toolDefaultFor($action)
    {
        return function ($url, $model = null, $key = null) use ($action) {
            $text = Yii::t('cruds', ucfirst($action));
            $label = '<span class="glyphicon glyphicon-new-window"></span>';
            $options = [
                'title' => $text,
                'aria-label' => $text,
                'data-pjax' => FALSE,
            ];
            return Html::a($label, $url, $options);
        };
    }

    /**
     * default tool generator for each actions available
     * @return array
     */
    public static function tools()
    {
        return [];
    }

    /**
     * get closure/function for tool generator
     * @param string$action
     * @return \closure
     */
    public static function toolFor($action)
    {
        $buttons = static::tools();

        if (isset($buttons[$action])) {
            return $buttons[$action];
        }

        return static::toolDefaultFor($action);
    }

    /**
     * create default closure/function for dropdown button generator
     * @param string $action
     * @return \Closure
     */
    public static function dropdownButtonDefaultFor($action)
    {
        return function ($url, $model = null, $key = null) use ($action) {
            $text = Yii::t('cruds', ucfirst($action));
            $label = '<span class="glyphicon glyphicon-new-window"></span> '.$text;
            $options = [
                'title' => $text,
                'aria-label' => $text,
                'data-pjax' => FALSE,
            ];
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
                return static::$dropdownSeparator;
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
        return function ($model, $key = null, $index = null) use ($action) {
            $action_control_class = static::actionControlFor($action);

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
        $html = '';
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
            $html .= call_user_func($generator, $url, $model);
        }

        return $html;
    }

    /**
     * 
     * @param string $actions
     * @param ActiveRecord $model
     * @return string the rendering result of the widget.
     */
    public static function renderToolbar($actions, ActiveRecord $model = null)
    {
        $html = '';
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
            $generator = static::toolFor($action);
            if (empty($generator) OR is_callable($generator) === FALSE) {
                continue;
            }

            // url
            $url = static::createUrlFor($action, $model);

            // render button
            $html .= call_user_func($generator, $url, $model);
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