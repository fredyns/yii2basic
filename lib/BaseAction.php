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
 * @property AccessControl $accessControl action accessControl
 */
class BaseAction extends \yii\base\Action
{
    /**
     * @var AccessControl action access control before execution
     */
    public $accessControl;

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

        if ($this->accessControl && (is_array($this->accessControl) === FALSE OR is_string($this->accessControl) === FALSE)) {
            throw new InvalidConfigException('Access control must extend from '.AccessControl::class.'.');
        }
    }

    /**
     * run access controll filter
     * and return answer whether user has access to run action
     * @param ActiveRecord $model
     * @return boolean
     */
    protected function accessControlFilter(ActiveRecord $model = NULL)
    {
        if (empty($this->accessControl)) {
            return TRUE;
        }

        $config = is_array($this->accessControl) ? $this->accessControl : ['class' => $this->accessControl];

        if ($model) {
            $config['model'] = $model;
        }

        $this->accessControl = AccessControl::build($config);

        return $this->accessControl->isPassed;
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
            $this->accessControl->setSessionMessages();

            return $this->controller->redirect($url);
        }

        // error messages
        throw $this->accessControl->exception();
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
                if ($model) {
                    return call_user_func($url, $model);
                } else {
                    return call_user_func($url);
                }
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