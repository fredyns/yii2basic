<?php

namespace app\controllers\sample;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use cornernote\returnurl\ReturnUrl;
use app\lib\sample\person\PersonAC;
use app\lib\sample\person\PersonSearch;
use app\models\sample\Person;

/**
 * This is the class for controller "PersonController".
 */
class PersonController extends Controller
{

    /**
     * Indexing all available Person models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonSearch;

        if ($searchModel->hasAttribute('is_deleted')) {
            $searchModel->setAttribute('is_deleted', 0);
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /**
         * TODO: adjust act control
         */
        PersonAC::catchError();
        if (!PersonAC::canIndex()) {
            PersonAC::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * List deleted Person models.
     * @return mixed
     */
    public function actionListDeleted()
    {
        $searchModel = new PersonSearch;

        if ($searchModel->hasAttribute('is_deleted')) {
            $searchModel->setAttribute('is_deleted', 1);
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /**
         * TODO: adjust act control
         */
        PersonAC::catchError();
        if (!PersonAC::canListDeleted()) {
            PersonAC::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        return $this->render('list-deleted', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Person models.
     * @return mixed
     */
    public function actionListArchive()
    {
        $searchModel = new PersonSearch;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /**
         * TODO: adjust act control
         */
        PersonAC::catchError();
        if (!PersonAC::canListArchive()) {
            PersonAC::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        return $this->render('list-archive', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Person model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Person the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
            if (($model = Person::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Display detail of Person model.
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        /**
         * TODO: adjust act control
         */
        PersonAC::catchError();
        if (!PersonAC::canView()) {
            PersonAC::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        return $this->render('view', [
                'model' => $model,
        ]);
    }

    /**
     * Creates a new Person model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // new model template
        $model = new Person;

        $form_submit = FALSE;
        if (Yii::$app->request->isPost) {
            // load submitted form values
            $form_submit = $model->load(Yii::$app->request->post());
        } else {
            // load predefine form values
            $model->load(Yii::$app->request->get());
        }

        /**
         * TODO: adjust act control
         * ensure loaded data consistent with user permission
         */
        PersonAC::catchError();
        if (!PersonAC::canCreate()) {
            PersonAC::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        try {
            if ($form_submit && $model->save()) {
                // redirect
                return $this->redirect(ReturnUrl::getUrl());
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        return $this->render('create', [
                'model' => $model,
        ]);
    }

    /**
     * Updates an existing Person model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        /**
         * TODO: adjust act control
         */
        PersonAC::catchError();
        if (!PersonAC::canUpdate()) {
            PersonAC::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(ReturnUrl::getUrl());
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Person model.
     * If deletion is successful, the browser will be redirected to the previous page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        /**
         * TODO: adjust act control
         */
        PersonAC::catchError();
        if (!PersonAC::canDelete()) {
            PersonAC::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        try {
            $model->delete();
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
            Yii::$app->getSession()->addFlash('error', $msg);
        }

        return $this->redirect(ReturnUrl::getUrl());
    }

    /**
     * Restore previously deleted Person model.
     * If restoration is successful, the browser will be redirected to the previous page.
     * @param integer $id
     * @return mixed
     */
    public function actionRestore($id)
    {
        $model = $this->findModel($id);

        /**
         * TODO: adjust act control
         */
        PersonAC::catchError();
        if (!PersonAC::canRestore()) {
            PersonAC::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        try {
            $model->restore();
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
            Yii::$app->getSession()->addFlash('error', $msg);
        }

        return $this->redirect(ReturnUrl::getUrl());
    }

}
