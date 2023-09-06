<?php

namespace app\modules\sispit\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sispit\models\SispitOrientacaoAcademica;
use app\modules\sispit\models\SispitPlanoRelatorio;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\filters\VerbFilter;

/**
 * SispitorientacaoacademicaController implements the CRUD actions for SispitOrientacaoAcademica model.
 */
class SispitorientacaoacademicaController extends Controller
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
     * Displays a single SispitOrientacaoAcademica model.
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
     * Creates a new SispitOrientacaoAcademica model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new SispitOrientacaoAcademica();
        $model->id_plano_relatorio = $id;

        $plano = SispitPlanoRelatorio::findOne($id);

        if(!$plano->isEditable())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');


        if ($model->load(Yii::$app->request->post())){
            $model->pit_rit = $plano->isRitAvailable() ? 1 : 0;

            if($model->save())
                return $this->redirect(['sispitplanorelatorio/orientacao', 'id' => $model->id_plano_relatorio]);
        }
        
        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SispitOrientacaoAcademica model..
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
            return $this->redirect(['sispitplanorelatorio/orientacao', 'id' => $model->id_plano_relatorio]);
        
        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    public function actionValidate($id, $plano = false)
    {
        $model = $plano ? new SispitOrientacaoAcademica() : $this->findModel($id);

        if($plano)
            $model->id_plano_relatorio = $id;

        if ($model->load(Yii::$app->request->post())) {
            
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * Deletes an existing SispitOrientacaoAcademica model.
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

        return $this->redirect(['sispitplanorelatorio/orientacao', 'id' => $model->id_plano_relatorio]);
    }

    /**
     * Finds the SispitOrientacaoAcademica model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SispitOrientacaoAcademica the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SispitOrientacaoAcademica::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A página requisitada não existe.');
    }
}
