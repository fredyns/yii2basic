<?php

namespace app\lib;

use Yii;
use yii\base\InvalidConfigException;

/**
 * Generic action to index/browse models
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class IndexAction extends BaseAction
{
    public $searchClass;

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

        $searchModel = Yii::createObject($this->searchClass);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

}