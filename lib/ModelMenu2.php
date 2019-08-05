<?php

namespace app\lib;

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
class ModelMenu2 extends \app\base\BaseModelMenu2
{
    const INDEX = 'index';
    const VIEW = 'view';
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const DELETED = 'deleted';
    const RESTORE = 'restore';
    const ARCHIVE = 'archive';

    public static $controller = '';
    public static $softdelete = false;

    /**
     * list all available URI
     * @return string[]
     */
    public static function uris()
    {
        $uris = [
            static::INDEX => static::$controller.'/index',
            static::VIEW => static::$controller.'/view',
            static::CREATE => static::$controller.'/create',
            static::UPDATE => static::$controller.'/update',
            static::DELETE => static::$controller.'/delete',
        ];

        if (static::$softdelete) {
            $uris[static::DELETED] = static::$controller.'/deleted';
            $uris[static::RESTORE] = static::$controller.'/restore';
            $uris[static::ARCHIVE] = static::$controller.'/archive';
        }

        return $uris;
    }

    /**
     * list access control for all available actions
     * @return string[]
     */
    public static function actionControls()
    {
        // this list need to be overiden
        $action_controls = [
            static::INDEX => ActionControl::class,
            static::VIEW => ActionControl::class,
            static::CREATE => ActionControl::class,
            static::UPDATE => ActionControl::class,
            static::DELETE => ActionControl::class,
        ];

        if (static::$softdelete) {
            $action_controls[static::DELETED] = ActionControl::class;
            $action_controls[static::RESTORE] = ActionControl::class;
            $action_controls[static::ARCHIVE] = ActionControl::class;
        }

        return $action_controls;
    }

    /**
     * default button generator for each actions available
     * @return array
     */
    public static function buttons()
    {
        $buttons = [
            // button generator for 'index' action
            static::INDEX => function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'List');
                $label = '<span class="glyphicon glyphicon-list-alt"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                    'class' => 'btn',
                ];
                return Html::a($label, $url, $options);
            },
            // button generator for 'view' action
            static::VIEW => function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'View');
                $label = '<span class="glyphicon glyphicon-eye-open"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                    'class' => 'btn',
                ];
                return Html::a($label, $url, $options);
            },
            // button generator for 'create' action
            static::CREATE => function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Create');
                $label = '<span class="glyphicon glyphicon-plus"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                    'class' => 'btn',
                ];
                return Html::a($label, $url, $options);
            },
            // button generator for 'update' action
            static::UPDATE => function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Update');
                $label = '<span class="glyphicon glyphicon-pencil"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                    'class' => 'btn',
                ];
                return Html::a($label, $url, $options);
            },
            // button generator for 'delete' action
            static::DELETE => function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Delete');
                $label = '<span class="glyphicon glyphicon-trash"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-confirm' => Yii::t('cruds', 'Are you sure to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => FALSE,
                    'class' => 'btn btn-danger',
                ];
                return Html::a($label, $url, $options);
            },
        ];

        if (static::$softdelete) {
            // button generator for 'deleted' action
            $buttons[static::DELETED] = function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Deleted');
                $label = '<span class="glyphicon glyphicon-oil"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                    'class' => 'btn',
                ];
                return Html::a($label, $url, $options);
            };
            // button generator for 'restore' action
            $buttons[static::RESTORE] = function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Restore');
                $label = '<span class="glyphicon glyphicon-time"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-confirm' => Yii::t('cruds', 'Are you sure to restore this item?'),
                    'data-method' => 'post',
                    'data-pjax' => FALSE,
                    'class' => 'btn btn-warning',
                ];
                return Html::a($label, $url, $options);
            };
            // button generator for 'archive' action
            $buttons[static::ARCHIVE] = function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Archive');
                $label = '<span class="glyphicon glyphicon-hdd"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                    'class' => 'btn',
                ];
                return Html::a($label, $url, $options);
            };
        }

        return $buttons;
    }

    /**
     * default dropdown button generator for each actions available
     * @return array
     */
    public static function dropdownButtons()
    {
        $buttons = [
            // dropdown separator generator
            'divider' => function ($url = NULL, $model = null, $key = null) {
                return static::$dropdownSeparator;
            },
            // dropdown item generator for 'index' action
            static::INDEX => function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'List');
                $label = '<span class="glyphicon glyphicon-list-alt"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                ];
                return '<li>'.Html::a($label, $url, $options).'</li>';
            },
            // dropdown item generator for 'view' action
            static::VIEW => function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'View');
                $label = '<span class="glyphicon glyphicon-eye-open"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                ];
                return '<li>'.Html::a($label, $url, $options).'</li>';
            },
            // dropdown item generator for 'create' action
            static::CREATE => function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Create');
                $label = '<span class="glyphicon glyphicon-plus"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                ];
                return '<li>'.Html::a($label, $url, $options).'</li>';
            },
            // dropdown item generator for 'update' action
            static::UPDATE => function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Update');
                $label = '<span class="glyphicon glyphicon-pencil"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                ];
                return '<li>'.Html::a($label, $url, $options).'</li>';
            },
            // dropdown item generator for 'delete' action
            static::DELETE => function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Delete');
                $label = '<span class="glyphicon glyphicon-trash"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-confirm' => Yii::t('cruds', 'Are you sure to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => FALSE,
                    'class' => 'label label-danger',
                ];
                return '<li>'.Html::a($label, $url, $options).'</li>';
            },
        ];

        if (static::$softdelete) {
            // dropdown item generator for 'deleted' action
            $buttons[static::DELETED] = function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Deleted');
                $label = '<span class="glyphicon glyphicon-oil"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                ];
                return '<li>'.Html::a($label, $url, $options).'</li>';
            };
            // dropdown item generator for 'restore' action
            $buttons[static::RESTORE] = function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Restore');
                $label = '<span class="glyphicon glyphicon-time"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-confirm' => Yii::t('cruds', 'Are you sure to restore this item?'),
                    'data-method' => 'post',
                    'data-pjax' => FALSE,
                    'class' => 'label label-warning',
                ];
                return '<li>'.Html::a($label, $url, $options).'</li>';
            };
            // dropdown item generator for 'archive' action
            $buttons[static::ARCHIVE] = function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Archive');
                $label = '<span class="glyphicon glyphicon-hdd"></span> '.$text;
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                ];
                return '<li>'.Html::a($label, $url, $options).'</li>';
            };
        }

        return $buttons;
    }

    /**
     * default dropdown button generator for each actions available
     * @return array
     */
    public static function toolbarButtons()
    {
        $buttons = [
            // button generator for 'index' action
            static::INDEX => function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'List');
                $label = '<span class="glyphicon glyphicon-list-alt"></span>';
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                    'class' => 'btn',
                ];
                return Html::a($label, $url, $options);
            },
            // button generator for 'view' action
            static::VIEW => function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'View');
                $label = '<span class="glyphicon glyphicon-eye-open"></span>';
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                    'class' => 'btn',
                ];
                return Html::a($label, $url, $options);
            },
            // button generator for 'create' action
            static::CREATE => function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Create');
                $label = '<span class="glyphicon glyphicon-plus"></span>';
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                    'class' => 'btn',
                ];
                return Html::a($label, $url, $options);
            },
            // button generator for 'update' action
            static::UPDATE => function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Update');
                $label = '<span class="glyphicon glyphicon-pencil"></span>';
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                    'class' => 'btn',
                ];
                return Html::a($label, $url, $options);
            },
            // button generator for 'delete' action
            static::DELETE => function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Delete');
                $label = '<span class="glyphicon glyphicon-trash"></span>';
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-confirm' => Yii::t('cruds', 'Are you sure to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => FALSE,
                    'class' => 'btn btn-danger',
                ];
                return Html::a($label, $url, $options);
            },
        ];

        if (static::$softdelete) {
            // button generator for 'deleted' action
            $buttons[static::DELETED] = function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Deleted');
                $label = '<span class="glyphicon glyphicon-oil"></span>';
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                    'class' => 'btn',
                ];
                return Html::a($label, $url, $options);
            };
            // button generator for 'restore' action
            $buttons[static::RESTORE] = function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Restore');
                $label = '<span class="glyphicon glyphicon-time"></span>';
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-confirm' => Yii::t('cruds', 'Are you sure to restore this item?'),
                    'data-method' => 'post',
                    'data-pjax' => FALSE,
                    'class' => 'btn btn-warning',
                ];
                return Html::a($label, $url, $options);
            };
            // button generator for 'archive' action
            $buttons[static::ARCHIVE] = function ($url, $model = null, $key = null) {
                $text = Yii::t('cruds', 'Archive');
                $label = '<span class="glyphicon glyphicon-hdd"></span>';
                $options = [
                    'title' => $text,
                    'aria-label' => $text,
                    'data-pjax' => FALSE,
                    'class' => 'btn',
                ];
                return Html::a($label, $url, $options);
            };
        }

        return $buttons;
    }

    /**
     * @return array
     */
    public static function visibleButtons()
    {
        $visibilities = [
            static::INDEX => static::visibleButtonDefaultFor('index'),
            static::VIEW => static::visibleButtonDefaultFor('view'),
            static::CREATE => static::visibleButtonDefaultFor('create'),
            static::UPDATE => static::visibleButtonDefaultFor('update'),
            static::DELETE => static::visibleButtonDefaultFor('delete'),
        ];

        if (static::$softdelete) {
            $visibilities[static::DELETED] = static::visibleButtonDefaultFor('deleted');
            $visibilities[static::RESTORE] = static::visibleButtonDefaultFor('restore');
            $visibilities[static::ARCHIVE] = static::visibleButtonDefaultFor('archive');
        }

        return $visibilities;
    }

}