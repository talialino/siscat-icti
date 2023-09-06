<?php

namespace app\modules\sisrh\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sisrh\models\SisrhComissaoPessoa;
use app\modules\sisrh\models\SisrhComissaoPessoaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SisrhComissaopessoaController implements the CRUD actions for SisrhComissaoPessoa model.
 */
class SisrhcomissaopessoaController extends Controller
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
     * Creates a new SisrhComissaoPessoa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_comissao)
    {
        $model = new SisrhComissaoPessoa();
        $model->id_comissao = $id_comissao;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/sisrh/sisrhcomissao/update', 'id' => $model->id_comissao]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SisrhComissaoPessoa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($comissao, $pessoa)
    {
        $model = $this->findModel(['id_comissao' => $comissao, 'id_pessoa' => $pessoa]);
        $model->delete();

        return $this->redirect(['/sisrh/sisrhcomissao/update', 'id' => $comissao]);
    }

    /**
     * Finds the SisrhComissaoPessoa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisrhComissaoPessoa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisrhComissaoPessoa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
