<?php

namespace app\dictionaries;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Description of EnumField
 * all class extending this shall:
 * - create constants representing all value available
 * - extend all() function to declare all data
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
abstract class EnumField
{
    /**
     * all class extending this shall have some constant represent each value
     * 
     * For example,
     * ```php
     *   const NO = 0;
     *   const YES = 1;
     * ```
     */

    /**
     * resides all available data
     * 
     * For example,
     *
     * ```php
     *   return [
     *       [
     *           'value' => static::NO,
     *           'label' => Yii::t('dialog', "No"),
     *       ],
     *       [
     *           'value' => static::YES,
     *           'label' => Yii::t('dialog', "Yes"),
     *       ],
     *   ];
     * ```
     *
     * @return array
     */
    public static function all()
    {
        return [];
    }

    /**
     * get options available to select
     * 
     * @return string[]
     */
    public static function options($label_attr = 'label')
    {
        return ArrayHelper::map(static::all(), 'value', $label_attr);
    }

    /**
     * get all values available
     * 
     * @return int[]
     */
    public static function values()
    {
        return ArrayHelper::getColumn(static::all(), 'value');
    }

    /**
     * get all labels available
     * 
     * @return int[]
     */
    public static function labels($label_attr = 'label')
    {
        return ArrayHelper::getColumn(static::all(), $label_attr);
    }

    /**
     * get label for arbitary value
     * 
     * @param int $value
     * @return string
     */
    public static function getLabel($value, $label_attr = 'label')
    {
        return static::searchAndGet($value, 'value', $label_attr);
    }

    /**
     * get value for arbitary label
     * 
     * @param int $label
     * @return string
     */
    public static function getValue($label, $label_attr = 'label')
    {
        return static::searchAndGet($label, $label_attr, 'value');
    }

    /**
     * search text on particular column and return value on coresponding column
     * 
     * @param string $search
     * @param string $on_column
     * @param string $get_column
     * @return string
     */
    public static function searchAndGet($search, $on_column, $get_column = 'label')
    {
        foreach (static::all() as $item) {
            if (isset($item[$on_column]) && isset($item[$get_column]) && $item[$on_column] == $search) {
                return $item[$get_column];
            }
        }

        return NULL;
    }

}