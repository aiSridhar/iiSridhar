<?php

namespace app\controllers;

use app\models\MessagesInGroup;
use app\models\MessagesInGroupSearch;
use Yii;
use app\models\MessagesOut;
use app\models\MessagesOutSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MessagesOutController implements the CRUD actions for MessagesOut model.
 */
class MessagesOutController extends Controller
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
     * Lists all MessagesOut models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MessagesOutSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pageSize' => 100];

        //print_r(Yii::$app->request->queryParams);
        $r=Yii::$app->request->queryParams;
        $r["MessagesInGroupSearch"]=@$r["MessagesOutSearch"];
        $r["MessagesInGroupSearch"]["class_id"]=-1;
        //Yii::$app->request->queryParams["MessagesInGroupSearch"]
        /*
(
    [r] => messages-out/index
    [MessagesOutSearch] => Array
        (
            [id] =>
            [script_id] =>
            [step] =>
            [account_id] =>
            [ca_id] => 469106727498
            [engine] =>
        )

)*/

        $searchModelNoClass = new MessagesInGroupSearch();
        $dataProviderNoClass = $searchModelNoClass->search($r);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModelNoClass' => $searchModelNoClass,
            'dataProviderNoClass' => $dataProviderNoClass,
        ]);
    }

    /**
     * Displays a single MessagesOut model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MessagesOut model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MessagesOut();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MessagesOut model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MessagesOut model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MessagesOut model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MessagesOut the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MessagesOut::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
