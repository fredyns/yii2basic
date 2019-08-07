<?php

namespace app\components;

use Yii;
use kartik\grid\GridView;

/**
 * Description of ActionColumn
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class ActionColumn extends \kartik\grid\ActionColumn
{
    /**
     * @var \Closure for content renderer 
     */
    public $contentRenderer;
    public $width = '120px';

    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if ($this->contentRenderer instanceof \Closure) {
            return call_user_func($this->contentRenderer, $model, $key, $index);
        }

        return parent::renderDataCellContent($model, $key, $index);
    }

}