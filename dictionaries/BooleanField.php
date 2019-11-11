<?php

namespace app\dictionaries;

use Yii;

/**
 * Description of BooleanField
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class BooleanField extends EnumField
{
    const NO = 0;
    const YES = 1;

    /**
     * @inheritdoc
     */
    public static function all()
    {
        return [
            [
                'value' => static::NO,
                'label' => Yii::t('dictionaries/boolean', "No"),
            ],
            [
                'value' => static::YES,
                'label' => Yii::t('dictionaries/boolean', "Yes"),
            ],
        ];
    }

}