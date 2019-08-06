<?php

namespace app\widgets;

use Closure;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Display Splited Dropdown Button to saveral actions.
 * 
 * possible scenario:
 *  - all actions is runnable, main & dropdown action are runnable      ->  show split dropdown
 *  - main action runnable, dropdown action partially runnable          ->  show split dropdown
 *  - main action runnable, dropdown action all prohibited              ->  show button only
 *  - main action prohibited, dropdown action all runnable              ->  show dropdown w/o button
 *  - main action prohibited, dropdown action partially runnable        ->  show dropdown w/o button
 *  - main action prohibited, dropdown action all prohibited            ->  show nothing
 * 
 * @property-read Boolean $isVisible get this widget visibility
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class SplitDropdown extends \yii\bootstrap\ButtonDropdown
{
    /**
     * @var ActiveRecord coresponding model 
     */
    public $model;

    /**
     * property for main button
     * @var string action name to display in main button 
     */
    public $buttonAction = 'view';
    public $options = ['class' => 'btn btn-info'];

    /**
     * @var array of actions to display in dropdown 
     * support nested array to be separated by divider
     */
    public $dropdownActions = [];
    public $dropdownLabel;
    public $dropdown = [
        'encodeLabels' => FALSE,
        'options' => [
            'class' => 'dropdown-menu-right',
        ],
    ];
    public $dropdownButtons = [];

    /**
     *
     */
    public $split = true;
    public $tagName = 'a';
    public $urlCreator;
    public $divider = '<li role="presentation" class="divider"></li>';
    public $visibleButtons = [];

    /**
     * status for each action
     */
    protected $_filteredDropdownActions = [];
    protected $_isButtonVisible = true;
    protected $_isDropdownVisible = true;

    /**
     * Renders the widget.
     */
    public function run()
    {
        // check all action visibility
        if ($this->isVisible === FALSE) {
            //if failed, show noting
            return NULL;
        }

        // if all dropdown prohibited, show button only
        if ($this->_isDropdownVisible === FALSE) {
            $url = $this->createUrl($this->buttonAction);
            return Html::a($this->label, $url, $this->options);
        }

        // if main button runnable, set url
        if ($this->_isButtonVisible) {
            $this->options['href'] = $this->createUrl($this->buttonAction);
        } else {
            // if main button prohibited, disable main button
            $this->split = FALSE;
            $this->tagName = parent::tagName;
            $this->label = $this->dropdownLabel ? $this->dropdownLabel : Yii::t('cruds', 'Actions');
        }

        // get dropdown action list
        $this->dropdown['items'] = $this->getDropdownItems();

        // render widget
        return parent::run();
    }

    /**
     * check whether widget is visible
     * @return Bool
     */
    public function getIsVisible()
    {
        // main button visibility
        $this->_isButtonVisible = $this->isActionVisible($this->buttonAction);

        // dropdown buttons visibility
        $this->prepareDropdownItems();

        // conclusion
        return $this->_isButtonVisible OR $this->_isDropdownVisible;
    }

    /**
     * check action button visibility
     * @param string $action
     * @return boolean
     */
    public function isActionVisible($action)
    {
        if (isset($this->visibleButtons[$action]) == FALSE) {
            return TRUE;
        }

        if ($this->visibleButtons[$action] instanceof \Closure) {
            return call_user_func($this->visibleButtons[$action], $this->model);
        }

        return (bool) $this->visibleButtons[$action];
    }

    public function prepareDropdownItems()
    {
        if (empty($this->dropdownActions) OR is_array($this->dropdownActions) == FALSE) {
            $this->_isDropdownVisible = FALSE;
            return;
        }

        foreach ($this->dropdownActions as $key => $action) {
            // plain action name
            if (is_string($action)) {
                if ($this->isActionVisible($action)) {
                    $this->_filteredDropdownActions[$key] = $action;
                    continue;
                }
            }
            // list of actions
            if (is_array($action)) {
                foreach ($action as $subkey => $subaction) {
                    // only plain action name in sublist is accepted
                    if (is_string($subaction)) {
                        if ($this->isActionVisible($subaction)) {
                            $this->_filteredDropdownActions[$key][$subkey] = $subaction;
                        }
                    }
                }
                // this will limit only to 2 level of array
            }
        }

        // visibility
        $this->_isDropdownVisible = (bool) $this->_filteredDropdownActions;
    }

    /**
     * create url for particular action
     * @param string $action
     * @param ActiveRecord $model
     * @return array|string
     */
    public function createUrl($action)
    {
        if ($this->urlCreator instanceof Closure) {
            return call_user_func($this->urlCreator, $action, $this->model);
        }

        return [$action];
    }

    public function getDropdownItems()
    {
        $count = count($this->_filteredDropdownActions);
        $first_index = 1;
        $last_index = $count;

        $dropdown_items = [];
        $current_index = 0;
        $last_item = null;
        foreach ($this->_filteredDropdownActions as $action) {
            $current_index++;
            $not_first = ($current_index !== $first_index);
            $not_last = ($current_index !== $last_index);
            $is_grouped = is_array($action);

            if ($not_first && $is_grouped && $last_item !== 'divider') {
                $dropdown_items[] = $this->divider;
                $last_item = 'divider';
            }

            if (is_string($action)) {
                $dropdown_items[] = $this->createItem($action);
                $last_item = 'action';
            } elseif ($is_grouped) {
                foreach ($action as $subaction) {
                    $dropdown_items[] = $this->createItem($subaction);
                }
                $last_item = 'action';
            }

            if ($not_last && $is_grouped) {
                $dropdown_items[] = $this->divider;
                $last_item = 'divider';
            }
        }

        return $dropdown_items;
    }

    public function createItem($action)
    {
        if (isset($this->dropdownButtons[$action])) {
            $url = $this->createUrl($action);
            return call_user_func($this->dropdownButtons[$action], $url, $this->model);
        }

        return '<li>'.Html::a(\yii\helpers\Inflector::camel2words($action), [$action]).'</li>';
    }

}