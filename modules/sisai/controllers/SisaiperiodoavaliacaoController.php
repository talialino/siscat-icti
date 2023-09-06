<?php

namespace app\modules\sisai\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sisai\models\SisaiPeriodoAvaliacao;
use app\modules\sisai\models\SisaiPeriodoAvaliacaoSearch;
use app\modules\siscc\models\SisccComponenteCurricular;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * SisaiperiodoavaliacaoController implements the CRUD actions for SisaiPeriodoAvaliacao model.
 */
class SisaiperiodoavaliacaoController extends Controller
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
                        'allow' => Yii::$app->user->can('sisaiAdministrar'),
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
     * Lists all SisaiPeriodoAvaliacao models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SisaiPeriodoAvaliacaoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SisaiPeriodoAvaliacao model.
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
     * Creates a new SisaiPeriodoAvaliacao model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $session = Yii::$app->session;
        if($session->get('siscat_periodo_avaliacao', false))
            throw new ForbiddenHttpException('Não é possível criar um novo período de avaliação com outro ainda vigente.');
        $model = new SisaiPeriodoAvaliacao();

        $model->load(Yii::$app->request->post());

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $data = date('Y-m-d H:i:s');
            if($data >= $model->data_inicio && $data <= $model->data_fim)
                $session->set('siscat_periodo_avaliacao', $model);
            
            return $this->redirect(['view', 'id' => $model->id_semestre]);
        }
       
        $model->questionarios = $model::DEFAULT_QUESTIONARIOS;
        $componentes_estagio = SisccComponenteCurricular::find()->select('id_componente_curricular')->where(['>','ch_estagio',0])->asArray()->all();
        $temp = array();
        foreach($componentes_estagio as $componente)
            $temp[] = $componente['id_componente_curricular'];

        $model->componentes_estagio = $temp;

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SisaiPeriodoAvaliacao model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $session = Yii::$app->session;
        $periodoAvaliacao = $session->get('siscat_periodo_avaliacao', false);

        if($periodoAvaliacao && $periodoAvaliacao->id_semestre != $id)
            throw new ForbiddenHttpException('Não é possível editar um período de avaliação com outro ainda vigente.');
            
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            $data = date('Y-m-d H:i:s');
            if($data < $model->data_inicio || $data > $model->data_fim)
                $session->remove('siscat_periodo_avaliacao');
            else
                $session->set('siscat_periodo_avaliacao', $model);
            return $this->redirect(['view', 'id' => $model->id_semestre]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SisaiPeriodoAvaliacao model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        //Caso siscat_periodo_avaliacao esteja na sessão, remove
        Yii::$app->session->remove('siscat_periodo_avaliacao');

        return $this->redirect(['index']);
    }

    /**
     * Finds the SisaiPeriodoAvaliacao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisaiPeriodoAvaliacao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisaiPeriodoAvaliacao::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
