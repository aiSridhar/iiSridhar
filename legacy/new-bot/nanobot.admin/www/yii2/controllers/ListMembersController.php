<?php

namespace app\controllers;

use Yii;
use app\models\ListMembers;
use app\models\ListMembersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ListMembersController implements the CRUD actions for ListMembers model.
 */
class ListMembersController extends Controller
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
     * Lists all ListMembers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ListMembersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ListMembers model.
     * @param integer $list_id
     * @param string $engine
     * @param integer $ca_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($list_id, $engine, $ca_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($list_id, $engine, $ca_id),
        ]);
    }

    /**
     * Creates a new ListMembers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ListMembers();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'list_id' => $model->list_id, 'engine' => $model->engine, 'ca_id' => $model->ca_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ListMembers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $list_id
     * @param string $engine
     * @param integer $ca_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($list_id, $engine, $ca_id)
    {
        $model = $this->findModel($list_id, $engine, $ca_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'list_id' => $model->list_id, 'engine' => $model->engine, 'ca_id' => $model->ca_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ListMembers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $list_id
     * @param string $engine
     * @param integer $ca_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($list_id, $engine, $ca_id)
    {
        $this->findModel($list_id, $engine, $ca_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ListMembers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $list_id
     * @param string $engine
     * @param integer $ca_id
     * @return ListMembers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($list_id, $engine, $ca_id)
    {
        if (($model = ListMembers::findOne(['list_id' => $list_id, 'engine' => $engine, 'ca_id' => $ca_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
