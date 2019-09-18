<?php

namespace app\controllers\geo_address;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use cornernote\returnurl\ReturnUrl;
use app\lib\geo_address\type\TypeAC;
use app\lib\geo_address\type\TypeSearch;
use app\models\geo_address\Type;

/**
 * This is the class for controller "TypeController".
 */
class TypeController extends Controller
{

    /**
     * Indexing all available Type models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TypeSearch;

        if ($searchModel->hasAttribute('is_deleted')) {
            $searchModel->setAttribute('is_deleted', 0);
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /**
         * TODO: adjust act control
         */
        TypeAC::catchError();
        if (!TypeAC::canIndex()) {
            TypeAC::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Type model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Type the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
            if (($model = Type::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Display detail of Type model.
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        /**
         * TODO: adjust act control
         */
        TypeAC::catchError();
        if (!TypeAC::canView()) {
            TypeAC::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        return $this->render('view', [
                'model' => $model,
        ]);
    }

    /**
     * Creates a new Type model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // new model template
        $model = new Type;

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
        TypeAC::catchError();
        if (!TypeAC::canCreate()) {
            TypeAC::flashMessages();
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
     * Updates an existing Type model.
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
        TypeAC::catchError();
        if (!TypeAC::canUpdate()) {
            TypeAC::flashMessages();
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
     * Deletes an existing Type model.
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
        TypeAC::catchError();
        if (!TypeAC::canDelete()) {
            TypeAC::flashMessages();
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

}
