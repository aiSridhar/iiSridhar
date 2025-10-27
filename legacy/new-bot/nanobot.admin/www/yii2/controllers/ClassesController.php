<?php

namespace app\controllers;

use kartik\grid\EditableColumnAction;
use Yii;
use app\models\Classes;
use app\models\ClassesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ClassesController implements the CRUD actions for Classes model.
 */
class ClassesController extends Controller
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'edit-name' => [
                'class' => EditableColumnAction::class,
                'modelClass' => Classes::class,
                'outputValue' => function (Classes $model) {
                    /* @var $model Classes */
                    return $model->name;
                },
                'showModelErrors' => true,
                'errorOptions' => ['header' => '']
            ],
            'edit-keywords' => [
                'class' => EditableColumnAction::class,
                'modelClass' => Classes::class,
                'outputValue' => function (Classes $model) {
                    /* @var $model Classes */
                    return $model->keywords;
                },
                'showModelErrors' => true,
                'errorOptions' => ['header' => '']
            ],
            'edit-value' => [
                'class' => EditableColumnAction::class,
                'modelClass' => Classes::class,
                'outputValue' => function (Classes $model) {
                    /* @var $model Classes */
                    return $model->value;
                },
                'showModelErrors' => true,
                'errorOptions' => ['header' => '']
            ],
            'edit-example' => [
                'class' => EditableColumnAction::class,
                'modelClass' => Classes::class,
                'outputValue' => function (Classes $model) {
                    /* @var $model Classes */
                    return $model->example;
                },
                'showModelErrors' => true,
                'errorOptions' => ['header' => '']
            ],
        ];
    }

    /**
     * Lists all Classes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClassesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pageSize' => 100];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Classes model.
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
     * Creates a new Classes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Classes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Classes model.
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
     * Deletes an existing Classes model.
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
     * Finds the Classes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Classes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Classes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionBulk(){
echo("123");
    $action=Yii::$app->request->post('action');
    $selection=(array)Yii::$app->request->post('selection');//typecasting
    foreach($selection as $id){
        $e=Evento::findOne((int)$id);//make a typecasting
        //do your stuff
        $e->save();
    }
    }

}
