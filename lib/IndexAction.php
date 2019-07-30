<?php

namespace app\lib;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

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
         * running action actionControl to check whether user has priviledges to run action
         */
        $passed = $this->actionControlFilter();

        if ($passed === FALSE) {
            return $this->fallbackPage();
        }

        /* @var $searchModel ActiveRecord */
        $searchModel = Yii::createObject($this->searchClass);

        if (is_bool($this->is_deleted)) {
            if ($searchModel->hasAttribute('is_deleted')) {
                $searchModel->setAttribute('is_deleted', $this->is_deleted);
            } else {
                throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
            }
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render($this->view, [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

}