<?php

namespace app\controllers;

use Yii;
use app\models\Accounts;
use app\models\AccountsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccountsController implements the CRUD actions for Accounts model.
 */
class AccountsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Accounts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccountsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pageSize' => 200];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Accounts model.
     * @param integer $id
     * @param string $engine
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $engine)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $engine),
        ]);
    }

    /**
     * Creates a new Accounts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Accounts();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'engine' => $model->engine]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Accounts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $engine
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $engine)
    {
        $model = $this->findModel($id, $engine);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'engine' => $model->engine]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Accounts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $engine
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $engine)
    {
        $this->findModel($id, $engine)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Accounts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $engine
     * @return Accounts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $engine)
    {
        if (($model = Accounts::findOne(['id' => $id, 'engine' => $engine])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
