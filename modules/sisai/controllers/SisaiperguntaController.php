<?php

namespace app\modules\sisai\controllers;

use app\modules\sisai\models\SisaiGrupoPerguntas;
use Yii;
use yii\filters\AccessControl;
use app\modules\sisai\models\SisaiPergunta;
use app\modules\sisai\models\SisaiPerguntaSearch;
use app\modules\sisai\models\SisaiRespostaMultiplaEscolha;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SisaiperguntaController implements the CRUD actions for SisaiPergunta model.
 */
class SisaiperguntaController extends Controller
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
                        'allow' => Yii::$app->user->can('siscatAdmin'),
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
     * Lists all SisaiPergunta models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SisaiPerguntaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new SisaiPergunta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $grupoPerguntas = SisaiGrupoPerguntas::findOne($id);
        if($grupoPerguntas == null)
            throw new NotFoundHttpException('A página requisitada não existe.');
        $model = new SisaiPergunta();
        $model->id_grupo_perguntas = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/sisai/sisaigrupoperguntas/update', 'id' => $model->id_grupo_perguntas]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'grupoPerguntas' => $grupoPerguntas,
        ]);
    }

    /**
     * Updates an existing SisaiPergunta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/sisai/sisaigrupoperguntas/update', 'id' => $model->id_grupo_perguntas]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SisaiPergunta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $id_grupo_perguntas = $model->id_grupo_perguntas;
        $model->delete();

        return $this->redirect(['/sisai/sisaigrupoperguntas/update', 'id' => $id_grupo_perguntas]);
    }

    public function actionAlternativas($id)
    {
        $model = new SisaiRespostaMultiplaEscolha();
        $model->id_pergunta = $id;

        if ($model->load(Yii::$app->request->post())) {
            if($model->salvarMultiplasRespostas())
                return $this->redirect(['/sisai/sisaigrupoperguntas/update', 'id' => $model->pergunta->id_grupo_perguntas]);
        }

        return $this->renderAjax('alternativas', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the SisaiPergunta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisaiPergunta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisaiPergunta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A página requisitada não existe.');
    }
}
