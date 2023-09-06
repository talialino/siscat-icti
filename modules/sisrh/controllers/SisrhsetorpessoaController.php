<?php

namespace app\modules\sisrh\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sisrh\models\SisrhSetorPessoa;
use app\modules\sisrh\models\SisrhSetorPessoaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SisrhSetorpessoaController implements the CRUD actions for SisrhSetorPessoa model.
 */
class SisrhsetorpessoaController extends Controller
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
                        'allow' => Yii::$app->user->can('sisrhsetor'),
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
     * Creates a new SisrhSetorPessoa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_setor)
    {
        $model = new SisrhSetorPessoa();
        $model->id_setor = $id_setor;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/sisrh/sisrhsetor/update', 'id' => $model->id_setor]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SisrhSetorPessoa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($setor, $pessoa)
    {
        $model = $this->findModel(['id_setor' => $setor, 'id_pessoa' => $pessoa]);
        $model->delete();

        return $this->redirect(['/sisrh/sisrhsetor/update', 'id' => $setor]);
    }

    /**
     * Finds the SisrhSetorPessoa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisrhSetorPessoa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisrhSetorPessoa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
