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
    public static function options()
    {
        return ArrayHelper::map(static::all(), 'value', 'label');
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
    public static function labels()
    {
        return ArrayHelper::getColumn(static::all(), 'label');
    }

    /**
     * get label for arbitary value
     * 
     * @param int $value
     * @return string
     */
    public static function getLabel($value)
    {
        $options = static::options();

        return ArrayHelper::getValue($options, $value, $value);
    }

    /**
     * get value for arbitary label
     * 
     * @param int $label
     * @return string
     */
    public static function getValue($label)
    {
        $options = ArrayHelper::map(static::all(), 'label', 'value');

        return ArrayHelper::getValue($options, $label);
    }

}