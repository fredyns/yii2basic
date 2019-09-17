<?php

use yii\helpers\StringHelper;

/* @var $this \yii\web\View  */
/* @var $generator \app\generators\crud\Generator  */
/* @var $tableSchema \yii\db\TableSchema  */
/* @var $softdelete bool  */
/* @var $modelClassName string  */
/* @var $modelSlug string  */
/* @var $modelName string  */
/* @var $model \yii\db\ActiveRecord  */
/* @var $searchClassName string search model class name w/o namespace  */
/* @var $acNameSpace string action control namespace */
/* @var $acClassName string action control class name w/o namespace */
/* @var $controllerClassName string  */
/* @var $controllerNameSpace string  */
/* @var $moduleNameSpace string  */
/* @var $moduleId string  */
/* @var $subPath string  */
/* @var $apiNameSpace string  */
/* @var $dateRange string[]  */
/* @var $timestampRange string[]  */

$pks = $tableSchema->primaryKey;
$searchClassName = StringHelper::basename($generator->searchModelClass);
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= $controllerNameSpace ?>;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use cornernote\returnurl\ReturnUrl;
use <?= $acNameSpace ?>\<?= $acClassName ?>;
use <?= $generator->searchModelClass ?>;
use <?= $generator->modelClass ?>;

/**
 * This is the class for controller "<?= $controllerClassName ?>".
 */
class <?= $controllerClassName ?> extends Controller
{

    /**
     * Indexing all available <?= $modelClassName ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new <?= $searchClassName ?>;

        if ($searchModel->hasAttribute('is_deleted')) {
            $searchModel->setAttribute('is_deleted', 0);
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /**
         * TODO: adjust act control
         */
        <?= $acClassName ?>::catchError();
        if (!<?= $acClassName ?>::canIndex()) {
            <?= $acClassName ?>::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

<?php if ($softdelete): ?>
    /**
     * List deleted <?= $modelClassName ?> models.
     * @return mixed
     */
    public function actionListDeleted()
    {
        $searchModel = new <?= $searchClassName ?>;

        if ($searchModel->hasAttribute('is_deleted')) {
            $searchModel->setAttribute('is_deleted', 1);
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /**
         * TODO: adjust act control
         */
        <?= $acClassName ?>::catchError();
        if (!<?= $acClassName ?>::canListDeleted()) {
            <?= $acClassName ?>::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        return $this->render('list-deleted', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all <?= $modelClassName ?> models.
     * @return mixed
     */
    public function actionListArchive()
    {
        $searchModel = new <?= $searchClassName ?>;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /**
         * TODO: adjust act control
         */
        <?= $acClassName ?>::catchError();
        if (!<?= $acClassName ?>::canListArchive()) {
            <?= $acClassName ?>::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        return $this->render('list-archive', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

<?php endif; ?>
    /**
     * Finds the <?= $modelClassName ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments)."\n" ?>
     * @return <?= $modelClassName ?> the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {
    <?php
    if (count($pks) === 1) {
        $condition = '$'.$pks[0];
    } else {
        $condition = [];
        foreach ($pks as $pk) {
            $condition[] = "'$pk' => \$$pk";
        }
        $condition = '['.implode(', ', $condition).']';
    }
    ?>
        if (($model = <?= $modelClassName ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Display detail of <?= $modelClassName ?> model.
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);

        /**
         * TODO: adjust act control
         */
        <?= $acClassName ?>::catchError();
        if (!<?= $acClassName ?>::canView()) {
            <?= $acClassName ?>::flashMessages();
            return $this->redirect(ReturnUrl::getUrl());
        }

        return $this->render('view', [
                'model' => $model,
        ]);
    }

    /**
     * Creates a new <?= $modelClassName ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // new model template
        $model = new <?= $modelClassName ?>;

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
        <?= $acClassName ?>::catchError();
        if (!<?= $acClassName ?>::canCreate()) {
            <?= $acClassName ?>::flashMessages();
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
     * Updates an existing <?= $modelClassName ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n\t * ", $actionParamComments)."\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);

        /**
         * TODO: adjust act control
         */
        <?= $acClassName ?>::catchError();
        if (!<?= $acClassName ?>::canUpdate()) {
            <?= $acClassName ?>::flashMessages();
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
     * Deletes an existing <?= $modelClassName ?> model.
     * If deletion is successful, the browser will be redirected to the previous page.
     * <?= implode("\n\t * ", $actionParamComments)."\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);

        /**
         * TODO: adjust act control
         */
        <?= $acClassName ?>::catchError();
        if (!<?= $acClassName ?>::canDelete()) {
            <?= $acClassName ?>::flashMessages();
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

<?php if ($softdelete): ?>
    /**
     * Restore previously deleted <?= $modelClassName ?> model.
     * If restoration is successful, the browser will be redirected to the previous page.
     * <?= implode("\n\t * ", $actionParamComments)."\n" ?>
     * @return mixed
     */
    public function actionRestore(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);

        /**
         * TODO: adjust act control
         */
        <?= $acClassName ?>::catchError();
        if (!<?= $acClassName ?>::canRestore()) {
            <?= $acClassName ?>::flashMessages();
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

<?php endif; ?>
}
