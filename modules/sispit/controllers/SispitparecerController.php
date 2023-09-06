<?php

namespace app\modules\sispit\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sispit\models\SispitParecer;
use app\modules\sispit\models\SispitParecerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use app\modules\sispit\models\SispitPlanoRelatorio;
use app\modules\sisrh\models\SisrhPessoa;

/**
 * SispitparecerController implements the CRUD actions for SispitParecer model.
 */
class SispitparecerController extends Controller
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
                        'allow' => Yii::$app->user->can('sispitDocente',['id' => Yii::$app->user->id]),
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
     * Lists all SispitParecer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $ano = Yii::$app->session->get('siscat_ano');
        $pessoa = Yii::$app->session->get('siscat_pessoa');
        $searchModel = new SispitParecerSearch();
        $dataProvider = $searchModel->search(['id_ano' => $ano->id_ano, 'id_pessoa' => $pessoa->id_pessoa]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRecebido($id)
    {
        $plano = SispitPlanoRelatorio::findOne($id);

        if( $plano == null || !Yii::$app->user->can('sispitVisualizar',['id' => $plano->user_id]))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        $searchModel = new SispitParecerSearch();
        $dataProvider = $searchModel->search(['id_plano_relatorio' => $id]);

        return $this->render('recebido', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing SispitParecer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $plano = $model->planoRelatorio;

        if(!$model->isEditable())
            throw new ForbiddenHttpException('Você não tem permissão para acessar essa página.');
        

        if ($model->load(Yii::$app->request->post()) && $plano->load(Yii::$app->request->post())) {
            $model->data = date('Y-m-d');
            $transaction = $model::getDb()->beginTransaction();
            try{
                if($model->save() && $plano->atualizarStatus(true))
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
     * Deletes an existing SispitParecer model.
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

    /**
     * Finds the SispitParecer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SispitParecer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SispitParecer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A página requisitada não existe.');
    }
}
