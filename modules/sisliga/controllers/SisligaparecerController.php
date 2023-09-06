<?php

namespace app\modules\sisliga\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sisliga\models\SisligaParecer;
use app\modules\sisliga\models\SisligaParecerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;


/**
 * SisligaparecerController implements the CRUD actions for SisligaParecer model.
 */
class SisligaparecerController extends Controller
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
     * Lists all SisligaParecer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $pessoa = Yii::$app->session->get('siscat_pessoa');
        $searchModel = new SisligaParecerSearch(); 
        $dataProvider = $searchModel->listaPareceres($pessoa->id_pessoa);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing SisligaParecer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $documento = $model->documento;

        if(!$model->isEditable())
            throw new ForbiddenHttpException('Você não tem permissão para acessar essa página.');
        
        if ($model->load(Yii::$app->request->post()) && $documento->load(Yii::$app->request->post())) {
            
            
            $model->data = date('Y-m-d');
            $transaction = $model::getDb()->beginTransaction();
            try{
                if($model->save() && $documento->atualizarSituacao(true))
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
     * Deletes an existing SisligaParecer model.
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

    public function actionViewLiga($id)
    {
        $searchModel = new SisligaParecerSearch();
        $params = ['id_liga_academica' => $id];
        $dataProvider = $searchModel->search($params);

        return $this->renderPartial('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewRelatorio($id)
    {
        $searchModel = new SisligaParecerSearch();
        $params = ['id_relatorio' => $id];
        $dataProvider = $searchModel->search($params);

        return $this->renderPartial('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the SisligaParecer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisligaParecer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisligaParecer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A página requisitada não existe.');
    }
}
