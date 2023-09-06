<?php

namespace app\modules\sispit\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sispit\models\SispitAno;
use app\modules\sispit\models\SispitAnoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

/**
 * SispitanoController implements the CRUD actions for SispitAno model.
 */
class SispitanoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['selecionarano','select'],
                        'allow' => Yii::$app->user->can('sispit'),
                    ],
                    [
                        'allow' => Yii::$app->user->can('sispitAdministrar'),
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SispitAno models.
     * @return mixed
     */
    public function actionSelecionarano()
    {
        $searchModel = new SispitAnoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('selecionarano', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SispitAno model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionSelect($id)
    {
        $model = $this->findModel($id);

        $session = Yii::$app->session;
        $session->set('siscat_ano', $model);

        return $this->redirect(['/sispit']);
    }

    public function actionIndex()
    {
        $searchModel = new SispitAnoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     /**
     * Displays a single SispitAno model.
     * @param integer $ano
     * @param integer $suplementar
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
     * Creates a new SispitAno model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SispitAno();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SispitAno model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $ano
     * @param integer $suplementar
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SispitAno model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $ano
     * @param integer $suplementar
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SispitAno model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $ano
     * @param integer $suplementar
     * @return SispitAno the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SispitAno::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A página solicitada não foi encontrada.');
    }
}
