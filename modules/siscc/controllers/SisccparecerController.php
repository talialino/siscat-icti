<?php

namespace app\modules\siscc\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\siscc\models\SisccParecer;
use app\modules\siscc\models\SisccParecerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use app\modules\siscc\models\SisccProgramaComponenteCurricular;


/**
 * SisccparecerController implements the CRUD actions for SisccParecer model.
 */
class SisccparecerController extends Controller
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
                        'actions' => ['view'],
                        'allow' => Yii::$app->user->can('siscc'),
                    ],
                    [
                        'allow' => Yii::$app->user->can('sisccDocente'),
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
     * Lists all SisccParecer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $pessoa = Yii::$app->session->get('siscat_pessoa');
        $searchModel = new SisccParecerSearch();
        $dataProvider = $searchModel->listaPareceres($pessoa->id_pessoa);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing SisccParecer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $programa = $model->programaComponenteCurricular;

        if(!$model->isEditable())
            throw new ForbiddenHttpException('Você não tem permissão para acessar essa página.');
        
        if ($model->load(Yii::$app->request->post()) && $programa->load(Yii::$app->request->post())) {
            
            
            $model->data = date('Y-m-d');
            $transaction = $model::getDb()->beginTransaction();
            try{
                if($model->save() && $programa->atualizarSituacao(true))
                    $transaction->commit();

            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $t) {
                $transaction->rollBack();
                throw $t;
            }
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SisccParecer model.
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

    public function actionView($id)
    {
        $searchModel = new SisccParecerSearch();
        $params = ['id_programa_componente_curricular' => $id];
        $dataProvider = $searchModel->search($params);

        return $this->renderPartial('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the SisccParecer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisccParecer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisccParecer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A página requisitada não existe.');
    }
}
