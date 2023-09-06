<?php

namespace app\modules\sispit\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sispit\models\SispitAtividadeComplementar;
use app\modules\sispit\models\SispitAtividadeComplementarSuplementar;
use app\modules\sispit\models\SispitPlanoRelatorio;
use app\modules\sispit\models\SispitEnsinoComponente;
use app\modules\sispit\models\SispitOrientacaoAcademica;
use app\modules\sispit\config\LimiteCargaHoraria;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\filters\VerbFilter;

/**
 * SispitatividadecomplementarController implements the CRUD actions for SispitAtividadeComplementar model.
 */
class SispitatividadecomplementarController extends Controller
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
     * Updates an existing SispitAtividadeComplementar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $scenario)
    {
        $plano = SispitPlanoRelatorio::findOne($id);
        if(!Yii::$app->user->can('sispitGerenciar',['id' => $plano->user_id]))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');
        if(!$plano->isPitEditable() && !$plano->isRitEditable())
        throw new ForbiddenHttpException('Os valores não podem ser editados.');
        
        $model = $this->findModel($id);
        $model->scenario = $scenario;
        $model->load(Yii::$app->request->post());
        $model->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionValidate($id, $scenario = false)
    {
        $plano = SispitPlanoRelatorio::findOne($id);

        $model = $this->findModel($id);
        if($scenario)
            $model->scenario = $scenario;
        if ($model->load(Yii::$app->request->post())) {
        
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * Finds the SispitAtividadeComplementar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SispitAtividadeComplementar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Yii::$app->session->get('siscat_ano')->suplementar ? SispitAtividadeComplementarSuplementar::findOne($id) : SispitAtividadeComplementar::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
