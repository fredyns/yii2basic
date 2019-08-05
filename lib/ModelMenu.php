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
class ModelMenu extends \app\base\BaseModelMenu
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
     * get all action available
     * @return string[]
     */
    public static function actions()
    {
        $actions = [
            static::INDEX,
            static::VIEW,
            static::CREATE,
            static::UPDATE,
            static::DELETE,
        ];

        if (static::$softdelete) {
            $actions[] = static::DELETED;
            $actions[] = static::RESTORE;
            $actions[] = static::ARCHIVE;
        }

        return $actions;
    }

    /**
     * list all available URI
     * @return string[]
     */
    public static function uris()
    {
        $uris = [];
        foreach (static::actions() as $action) {
            $uris[$action] = static::$controller.'/'.$action;
        }

        return $uris;
    }

    /**
     * list label for all actions
     * @return string[]
     */
    public static function labels()
    {
        $labels = [
            static::INDEX => Yii::t('cruds', 'List'),
            static::VIEW => Yii::t('cruds', 'View'),
            static::CREATE => Yii::t('cruds', 'New'),
            static::UPDATE => Yii::t('cruds', 'Update'),
            static::DELETE => Yii::t('cruds', 'Delete'),
        ];

        if (static::$softdelete) {
            $labels[static::DELETED] = Yii::t('cruds', 'Deleted');
            $labels[static::RESTORE] = Yii::t('cruds', 'Restore');
            $labels[static::ARCHIVE] = Yii::t('cruds', 'Archive');
        }

        return $labels;
    }

    /**
     * list icons for all actions
     * @return string[]
     */
    public static function icons()
    {
        $labels = [
            static::INDEX => '<span class="glyphicon glyphicon-list-alt"></span>',
            static::VIEW => '<span class="glyphicon glyphicon-eye-open"></span>',
            static::CREATE => '<span class="glyphicon glyphicon-plus"></span>',
            static::UPDATE => '<span class="glyphicon glyphicon-pencil"></span>',
            static::DELETE => '<span class="glyphicon glyphicon-trash"></span>',
        ];

        if (static::$softdelete) {
            $labels[static::DELETED] = '<span class="glyphicon glyphicon-oil"></span>';
            $labels[static::RESTORE] = '<span class="glyphicon glyphicon-time"></span>';
            $labels[static::ARCHIVE] = '<span class="glyphicon glyphicon-hdd"></span>';
        }

        return $labels;
    }

    /**
     * list of link options for each actions available
     * @return array
     */
    public static function linkOptions()
    {
        $list = [];
        foreach (static::actions() as $action) {
            switch ($action) {
                case static::DELETE:
                    $list[$action] = [
                        'title' => static::labelFor($action),
                        'aria-label' => static::labelFor($action),
                        'data-confirm' => Yii::t('cruds', 'Are you sure to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => FALSE,
                    ];
                    break;
                case static::RESTORE:
                    $list[$action] = [
                        'title' => static::labelFor($action),
                        'aria-label' => static::labelFor($action),
                        'data-confirm' => Yii::t('cruds', 'Are you sure to restore this item?'),
                        'data-method' => 'post',
                        'data-pjax' => FALSE,
                    ];
                    break;
                default:
                    $list[$action] = static::linkOptionsDefaultFor($action);
                    break;
            }
        }

        return $list;
    }

    /**
     * list access control for all available actions
     * @return string[]
     */
    public static function actionControls()
    {
        $action_controls = [];
        foreach (static::actions() as $action) {
            $action_controls[$action] = ActionControl::class;
        }

        return $action_controls;
    }

    /**
     * default button generator for each actions available
     * @return array
     */
    public static function buttons()
    {
        $buttons = [];
        foreach (static::actions() as $action) {
            $buttons[$action] = static::buttonDefaultFor($action);
        }

        return $buttons;
    }

    /**
     * default dropdown button generator for each actions available
     * @return array
     */
    public static function dropdownButtons()
    {
        $buttons = parent::dropdownButtons();
        foreach (static::actions() as $action) {
            $buttons[$action] = static::dropdownButtonDefaultFor($action);
        }

        return $buttons;
    }

    /**
     * @return array
     */
    public static function visibleButtons()
    {
        $visibilities = [];
        foreach (static::actions() as $action) {
            $visibilities[$action] = static::visibleButtonDefaultFor($action);
        }

        return $visibilities;
    }

}