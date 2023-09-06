<?php

namespace app\modules\sisliga\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sisliga\models\SisligaLigaIntegrante;
use app\modules\sisliga\models\SisligaIntegranteExterno;
use app\modules\sisliga\models\IntegranteForm;
use app\modules\sisliga\models\SisligaLigaIntegranteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * SisligaligaintegranteController implements the CRUD actions for SisligaLigaIntegrante model.
 */
class SisligaligaintegranteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => Yii::$app->user->can('sisliga'),
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SisligaLigaIntegrante models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SisligaLigaIntegranteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SisligaLigaIntegrante model.
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
     * Creates a new IntegranteForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new IntegranteForm();
        $model->id_liga_academica = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/sisliga/sisligaligaacademica/update', 'id' => $model->id_liga_academica, 'tab' => 1]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionValidate($id)
    {
        $model = new IntegranteForm();
        $model->id_liga_academica = $id;

        if ($model->load(Yii::$app->request->post())) {
        
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * Updates an existing SisligaLigaIntegrante model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $liga = $this->findModel($id);
        $model = new IntegranteForm();
        $model->carregarLigaIntegrante($liga);

        if ($model->load(Yii::$app->request->post()) && $model->save($liga)) {
            return $this->redirect(['/sisliga/sisligaligaacademica/update', 'id' => $model->id_liga_academica, 'tab' => 1]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SisligaLigaIntegrante model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $id_liga_academica = $model->id_liga_academica;
        $model->delete();

        return $this->redirect(['/sisliga/sisligaligaacademica/update', 'id' => $id_liga_academica, 'tab' => 1]);
    }

    /**
     * Finds the SisligaLigaIntegrante model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisligaLigaIntegrante the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisligaLigaIntegrante::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
