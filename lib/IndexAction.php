<?php

namespace app\lib;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Generic action to index/browse models
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class IndexAction extends BaseAction
{
    public $searchClass;
    public $is_deleted = NULL;
    public $view = 'index';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (empty($this->searchClass)) {
            throw new InvalidConfigException('Search class must be defined.');
        }
    }

    /**
     * execute action
     * @return mixed
     */
    public function run()
    {
        /**
         * running action accessControl to check whether user has priviledges to run action
         */
        $passed = $this->accessControlFilter();

        if ($passed === FALSE) {
            return $this->fallbackPage();
        }

        /* @var $searchModel ActiveRecord */
        $searchModel = Yii::createObject($this->searchClass);

        if (is_bool($this->is_deleted) && $searchModel->hasAttribute('is_deleted')) {
            $searchModel->setAttribute('is_deleted', $this->is_deleted);
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render($this->view, [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

}