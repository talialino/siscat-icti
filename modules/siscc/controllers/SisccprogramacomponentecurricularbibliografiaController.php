<?php

namespace app\modules\siscc\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\siscc\models\SisccProgramaComponenteCurricularBibliografia;
use app\modules\siscc\models\SisccBibliografia;
use app\modules\siscc\models\SisccSemestre;
use app\modules\siscc\models\SisccProgramaComponenteCurricularBibliografiaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SisccprogramacomponentecurricularbibliografiaController implements the CRUD actions for SisccProgramaComponenteCurricularBibliografia model.
 */
class SisccprogramacomponentecurricularbibliografiaController extends Controller
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
                        'allow' => Yii::$app->user->can('siscc'),
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
     * Lists all SisccProgramaComponenteCurricularBibliografia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SisccProgramaComponenteCurricularBibliografiaSearch();
        $searchModel->setAttributes(['programaComponenteCurricular.id_semestre' => SisccSemestre::find()->max('id_semestre')]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SisccProgramaComponenteCurricularBibliografia model.
     * @param integer $id_programa_componente_curricular
     * @param integer $id_bibliografia
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_programa_componente_curricular, $id_bibliografia)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_programa_componente_curricular, $id_bibliografia),
        ]);
    }

    /**
     * Creates a new SisccProgramaComponenteCurricularBibliografia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id, $colegiado = false)
    {
        $programaBibliografia = new SisccProgramaComponenteCurricularBibliografia();
        $programaBibliografia->id_programa_componente_curricular = $id;

        $bibliografia = new SisccBibliografia();

        if ($bibliografia->load(Yii::$app->request->post()) && $programaBibliografia->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try{
                if($bibliografia->save()){
                    $programaBibliografia->id_bibliografia = $bibliografia->id_bibliografia;
                    if($programaBibliografia->save())
                        $transaction->commit();
                }
            }
            catch(Exception $e)
            {
                $transaction->rollBack();
            }
            return $this->redirect([$colegiado ? '/siscc/gerenciarcolegiado/editarprograma' : '/siscc/sisccprogramacomponentecurricular/editar',
                'id' => $programaBibliografia->id_programa_componente_curricular,
                'showAbaBibliografia' => true]);
        }

        return $this->renderAjax('_form', [
            'programaBibliografia' => $programaBibliografia,
            'bibliografia' => $bibliografia,
        ]);
    }

    /**
     * Updates an existing SisccProgramaComponenteCurricularBibliografia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id_programa_componente_curricular
     * @param integer $id_bibliografia
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_programa_componente_curricular, $id_bibliografia)
    {
        $model = $this->findModel($id_programa_componente_curricular, $id_bibliografia);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_programa_componente_curricular' => $model->id_programa_componente_curricular, 'id_bibliografia' => $model->id_bibliografia]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SisccProgramaComponenteCurricularBibliografia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id_programa_componente_curricular
     * @param integer $id_bibliografia
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_programa_componente_curricular, $id_bibliografia)
    {
        $this->findModel($id_programa_componente_curricular, $id_bibliografia)->delete();

        return $this->redirect(['/siscc/sisccprogramacomponentecurricular/editar', 'id' => $id_programa_componente_curricular, 'showAbaBibliografia' => true]);
    }

    /**
     * Finds the SisccProgramaComponenteCurricularBibliografia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id_programa_componente_curricular
     * @param integer $id_bibliografia
     * @return SisccProgramaComponenteCurricularBibliografia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_programa_componente_curricular, $id_bibliografia)
    {
        if (($model = SisccProgramaComponenteCurricularBibliografia::findOne([
                'id_programa_componente_curricular' => $id_programa_componente_curricular,
                'id_bibliografia' => $id_bibliografia])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
