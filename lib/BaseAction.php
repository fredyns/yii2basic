<?php

namespace app\lib;

use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\UserException;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use cornernote\returnurl\ReturnUrl;

/**
 * Description of WebAction
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 *
 */
class BaseAction extends \yii\base\Action
{
    /**
     * @var ActionControl action access control before execution
     */
    public $actionControl;

    /**
     * @var array redirect url when user not able to run action
     */
    public $fallbackUrl;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (empty($this->fallbackUrl)) {
            $this->fallbackUrl = Yii::$app->homeUrl;
        }

        if ($this->actionControl && (is_array($this->actionControl) === FALSE OR is_string($this->actionControl) === FALSE)) {
            throw new InvalidConfigException('Access control must extend from '.ActionControl::class.'.');
        }
    }

    /**
     * run access controll filter
     * and return answer whether user has access to run action
     * @param ActiveRecord $model
     * @return boolean
     */
    protected function actionControlFilter(ActiveRecord $model = NULL)
    {
        if (empty($this->actionControl)) {
            return TRUE;
        }

        $config = is_array($this->actionControl) ? $this->actionControl : ['class' => $this->actionControl];

        if ($model) {
            $config['model'] = $model;
        }

        $this->actionControl = ActionControl::build($config);

        return $this->actionControl->isPassed;
    }

    /**
     * display fallback page when user is not permitted to run action
     * @param ActiveRecord $model
     * @return Mixed
     * @throws NotFoundHttpException
     */
    protected function fallbackPage(ActiveRecord $model = NULL)
    {
        $url = $this->resolveFallbackUrl($model);

        if ($url) {
            // set message to session and redirect to fallback url
            $this->actionControl->setSessionMessages();

            return $this->controller->redirect($url);
        }

        // error messages
        throw $this->actionControl->exception();
    }

    /**
     * resolve url path
     *
     * @param ActiveRecord $model
     * @return array
     */
    protected function resolveUrl($url, $model = NULL)
    {
        if ($url) {
            if (is_array($url) OR is_string($url)) {
                return $url;
            }

            if (is_callable($url) && $model) {
                return call_user_func($url, $model);
            }
        }

        return ReturnUrl::getUrl();
    }

    /**
     * resolve url to fallback when deletion failed
     *
     * @param \yii\db\ActiveRecord $model
     * @return array
     */
    protected function resolveFallbackUrl($model)
    {
        if (empty($this->fallbackUrl)) {
            return NULL;
        }

        return $this->resolveUrl($this->fallbackUrl, $model);
    }

}