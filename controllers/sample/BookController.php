<?php

namespace app\controllers\sample;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use cornernote\returnurl\ReturnUrl;
use app\lib\sample\book\BookAC;
use app\lib\sample\book\BookSearch;
use app\models\sample\Book;

/**
 * This is the class for controller "BookController".
 */
class BookController extends Controller
{

    /**
     * Indexing all available Book models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookSearch;

        if ($searchModel->hasAttribute('is_deleted')) {
            $searchModel->setAttribute('is_deleted', 0);
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /**
         * TODO: adjust act control
         */
        BookAC::catchError();
        if (!BookAC::canIndex()) {
            BookAC::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * List deleted Book models.
     * @return mixed
     */
    public function actionListDeleted()
    {
        $searchModel = new BookSearch;

        if ($searchModel->hasAttribute('is_deleted')) {
            $searchModel->setAttribute('is_deleted', 1);
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /**
         * TODO: adjust act control
         */
        BookAC::catchError();
        if (!BookAC::canListDeleted()) {
            BookAC::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        return $this->render('list-deleted', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Book models.
     * @return mixed
     */
    public function actionListArchive()
    {
        $searchModel = new BookSearch;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /**
         * TODO: adjust act control
         */
        BookAC::catchError();
        if (!BookAC::canListArchive()) {
            BookAC::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        return $this->render('list-archive', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Book the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
            if (($model = Book::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Display detail of Book model.
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        /**
         * TODO: adjust act control
         */
        BookAC::catchError();
        if (!BookAC::canView()) {
            BookAC::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        return $this->render('view', [
                'model' => $model,
        ]);
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // new model template
        $model = new Book;

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
        BookAC::catchError();
        if (!BookAC::canCreate()) {
            BookAC::flashMessages();
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
     * Updates an existing Book model.
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
        BookAC::catchError();
        if (!BookAC::canUpdate()) {
            BookAC::flashMessages();
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
     * Deletes an existing Book model.
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
        BookAC::catchError();
        if (!BookAC::canDelete()) {
            BookAC::flashMessages();
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
     * Restore previously deleted Book model.
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
        BookAC::catchError();
        if (!BookAC::canRestore()) {
            BookAC::flashMessages();
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
