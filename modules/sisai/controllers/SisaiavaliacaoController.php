<?php

namespace app\modules\sisai\controllers;

use app\modules\sisai\models\AdicionarComponentesForm;
use Yii;
use yii\filters\AccessControl;
use app\modules\sisai\models\SisaiAvaliacao;
use app\modules\sisai\models\SisaiAvaliacaoSearch;
use app\modules\sisai\models\SisaiProfessorComponenteCurricular;
use app\modules\siscc\models\SisccProgramaComponenteCurricular;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * SisaiavaliacaoController implements the CRUD actions for SisaiAvaliacao model.
 */
class SisaiavaliacaoController extends Controller
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
                        'actions' => ['discente'],
                        'allow' => Yii::$app->user->can('Discente'),
                    ],
                    [
                        'actions' => ['docente'],
                        'allow' => Yii::$app->user->can('Docente'),
                    ],
                    [
                        'actions' => ['tecnico'],
                        'allow' => Yii::$app->user->can('Tecnico'),
                    ],
                    [
                        'actions' => ['index', 'update', 'delete', 'view'],
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
     * Lists all SisaiAvaliacao models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SisaiAvaliacaoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SisaiAvaliacao model.
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
     * Updates an existing SisaiAvaliacao model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_avaliacao]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SisaiAvaliacao model.
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
     * Finds the SisaiAvaliacao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisaiAvaliacao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisaiAvaliacao::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionDiscente()
    {
        $avaliacao = SisaiAvaliacao::avaliacaoAtual(0);

        switch($avaliacao->situacao)
        {
            case 0:
                return $this->adicionarComponentes($avaliacao);
            case 99:
                if(Yii::$app->request->isPost)
                    return $this->adicionarComponentes($avaliacao);
                return $this->render('finaldiscente', ['avaliacao' => $avaliacao]);
        };

        $model = $avaliacao->questionarioForm;

        if($model == null && $avaliacao->atualizarSituacao())
            return $this->redirect('discente');

        if(Yii::$app->request->isPost && $model->carregarQuestionario(Yii::$app->request->post()))
            return $this->redirect('discente');

        return $this->render('questionario',['model' => $model]);
    }

    public function actionDocente($pularavaliacaocolegiado = 0, $importarnovoscomponentes = 0)
    {
        $avaliacao = SisaiAvaliacao::avaliacaoAtual(1);
        
        switch($avaliacao->situacao)
        {
            case 0:
                return $this->importarComponentes($avaliacao);
            case 18:
                if($pularavaliacaocolegiado == 1 && $avaliacao->atualizarSituacao(true))
                        return $this->redirect('docente');
            break;
            case 99:
                if($importarnovoscomponentes)
                    return $this->importarComponentes($avaliacao);
                if(Yii::$app->request->isPost)
                    return $this->redirect(['docente','importarnovoscomponentes' => 1]);
                return $this->render('finaldocente', ['avaliacao' => $avaliacao]);
        };

        $model = $avaliacao->questionarioForm;

        if($model == null && $avaliacao->atualizarSituacao())
            return $this->redirect('docente');

        if(Yii::$app->request->isPost && $model->carregarQuestionario(Yii::$app->request->post()))
            return $this->redirect('docente');

        return $this->render('questionario',['model' => $model]);
    }

    public function actionTecnico()
    {
        $avaliacao = SisaiAvaliacao::avaliacaoAtual(2);
        switch($avaliacao->situacao)
        {
            case 0:
                $avaliacao->situacao = 19;
            break;
            case 99:
                return $this->render('finaltecnico');
        };

        $model = $avaliacao->questionarioForm;

        if(Yii::$app->request->isPost && $model->carregarQuestionario(Yii::$app->request->post()))
            return $this->redirect('tecnico');

        return $this->render('questionario',['model' => $model]);
    }

    protected function adicionarComponentes($avaliacao)
    {
        $model = new AdicionarComponentesForm();

        if ($model->load(Yii::$app->request->post()))
        {
            if($model->save($avaliacao))
                return $this->redirect('discente');

           throw new ServerErrorHttpException('Ocorreu um erro ao salvar os dados. Tente novamente mais tarde.');
        }

        return $this->render('adicionarcomponentes',['model' => $model]);
    }

    protected function importarComponentes($avaliacao)
    {
        $query = SisccProgramaComponenteCurricular::find();
        $query->joinWith('sisccProgramaComponenteCurricularPessoas AS pessoas');
        $query->where(['id_semestre' => $avaliacao->id_semestre, 'pessoas.id_pessoa' => Yii::$app->session->get('siscat_pessoa')->id_pessoa]);


        if(Yii::$app->request->isPost)
        {
            if(SisaiProfessorComponenteCurricular::importarComponentes($avaliacao,$query))
                return $this->redirect('docente');
           throw new ServerErrorHttpException('Ocorreu um erro ao salvar os dados. Tente novamente mais tarde.');
        }

        return $this->render('importarcomponentes',['query' => $query, 'avaliacao' => $avaliacao]);
    }
}
