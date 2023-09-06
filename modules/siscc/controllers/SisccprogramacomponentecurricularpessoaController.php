<?php

namespace app\modules\siscc\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\siscc\models\SisccProgramaComponenteCurricularPessoa;
use app\modules\siscc\models\SisccProgramaComponenteCurricularPessoaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SisccprogramacomponentecurricularpessoaController implements the CRUD actions for SisccProgramaComponenteCurricularPessoa model.
 */
class SisccprogramacomponentecurricularpessoaController extends Controller
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
                        'actions' => ['index'],
                        'allow' => Yii::$app->user->can('sisccDocente'),
                    ],
                    [
                        'allow' => Yii::$app->user->can('sisccAdministrarVinculo'),
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
     * Lists all SisccProgramaComponenteCurricularPessoa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SisccProgramaComponenteCurricularPessoaSearch();
        $dataProvider = $searchModel->search(Yii::$app->session->get('siscat_pessoa')->id_pessoa,
                Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SisccProgramaComponenteCurricularPessoa model.
     * @param integer $id_programa_componente_curricular
     * @param integer $id_pessoa
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionView($id_programa_componente_curricular, $id_pessoa)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_programa_componente_curricular, $id_pessoa),
        ]);
    }*/

    /**
     * Creates a new SisccProgramaComponenteCurricularPessoa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new SisccProgramaComponenteCurricularPessoa();
        $model->id_programa_componente_curricular = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SisccProgramaComponenteCurricularPessoa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id_programa_componente_curricular
     * @param integer $id_pessoa
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_programa_componente_curricular, $id_pessoa)
    {
        $model = $this->findModel($id_programa_componente_curricular, $id_pessoa);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_programa_componente_curricular' => $model->id_programa_componente_curricular, 'id_pessoa' => $model->id_pessoa]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SisccProgramaComponenteCurricularPessoa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id_programa_componente_curricular
     * @param integer $id_pessoa
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_programa_componente_curricular, $id_pessoa)
    {
        $this->findModel($id_programa_componente_curricular, $id_pessoa)->delete();

        return $this->redirect(['../siscc/sisccprogramacomponentecurricular/index']);
    }

    /**
     * Finds the SisccProgramaComponenteCurricularPessoa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id_programa_componente_curricular
     * @param integer $id_pessoa
     * @return SisccProgramaComponenteCurricularPessoa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_programa_componente_curricular, $id_pessoa)
    {
        if (($model = SisccProgramaComponenteCurricularPessoa::findOne(['id_programa_componente_curricular' => $id_programa_componente_curricular, 'id_pessoa' => $id_pessoa])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
