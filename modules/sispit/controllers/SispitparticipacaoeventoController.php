<?php

namespace app\modules\sispit\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sispit\models\SispitParticipacaoEvento;
use app\modules\sispit\models\SispitParticipacaoEventoSearch;
use app\modules\sispit\models\SispitPlanoRelatorio;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

/**
 * SispitparticipacaoeventoController implements the CRUD actions for SispitParticipacaoEvento model.
 */
class SispitparticipacaoeventoController extends Controller
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
                        'allow' => Yii::$app->user->can('sispit'),
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
     * Lists all SispitParticipacaoEvento models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $plano = SispitPlanoRelatorio::findOne($id);

        if(!Yii::$app->user->can('sispitGerenciar',['id' => $plano->user_id]))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        $searchModel = new SispitParticipacaoEventoSearch();
        $searchModel->id_plano_relatorio = $id;
        $searchModel->pit_rit = $plano->isRitAvailable()? 1 : 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'plano' => $plano,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SispitParticipacaoEvento model.
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
     * Creates a new SispitParticipacaoEvento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new SispitParticipacaoEvento();
        $model->id_plano_relatorio = $id;

        $plano = SispitPlanoRelatorio::findOne($id);

        if(!$plano->isEditable())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');


        if ($model->load(Yii::$app->request->post())){
            $model->pit_rit = $plano->isRitAvailable() ? 1 : 0;

            if($model->save())
                return $this->redirect(['sispitparticipacaoevento/index', 'id' => $model->id_plano_relatorio]);
        }
        
        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SispitParticipacaoEvento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $plano = $model->planoRelatorio;

        if(!$plano->isEditable())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(['sispitparticipacaoevento/index', 'id' => $model->id_plano_relatorio]);
        
        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    public function actionValidate($id, $plano = false)
    {
        $model = $plano ? new SispitAfastamentoDocente() : $this->findModel($id);

        if($plano)
            $model->id_plano_relatorio = $id;

        if ($model->load(Yii::$app->request->post())) {
            
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * Deletes an existing SispitParticipacaoEvento model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if(!$model->planoRelatorio->isEditable())
            throw new ForbiddenHttpException('Você não está autorizado a realizar esta operação.');
        $model->delete();

        return $this->redirect(['sispitparticipacaoevento/index', 'id' => $model->id_plano_relatorio]);
    }

    /**
     * Finds the SispitParticipacaoEvento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SispitParticipacaoEvento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SispitParticipacaoEvento::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
