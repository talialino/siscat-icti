<?php

namespace app\modules\sisape\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sisape\models\SisapeProjeto;
use app\modules\sisape\models\SisapeProjetoSearch;
use app\modules\sisape\models\SisapeFinanciamento;
use app\modules\sisape\models\SisapeProjetoIntegrante;
use app\modules\sisape\models\SisapeAtividade;
use app\modules\sisape\models\SisapeParecer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

/**
 * SisapeprojetoController implements the CRUD actions for SisapeProjeto model.
 */
class SisapeprojetoController extends Controller
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
                        'allow' => Yii::$app->user->can('sisape'),
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
     * Lists all SisapeProjeto models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SisapeProjetoSearch();
        $searchModel->id_pessoa = Yii::$app->session->get('siscat_pessoa')->id_pessoa;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SisapeProjeto model.
     * Somente podem visualizar esta página o coordenador do projeto, coordenação do núcleo a qual o projeto foi submetido e a copex
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $pessoa = Yii::$app->session->get('siscat_pessoa');

        if($model->id_pessoa != $pessoa->id_pessoa && !Yii::$app->user->can('sisapeAdministrar'))
            if(!$pessoa->getSisrhSetorPessoas()->where(['id_setor' => $model->id_setor])->andWhere(['<','funcao',2])->exists())
                throw new ForbiddenHttpException('Você não está autorizado a acessar esta página.');

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new SisapeProjeto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SisapeProjeto();

        $pessoa = Yii::$app->session->get('siscat_pessoa');
        if(SisapeProjeto::existePendencia($pessoa->id_pessoa))
            throw new ForbiddenHttpException('Você não pode criar novos projetos pois você possui projetos com relatórios pendentes.');
        $model->id_pessoa = $pessoa->id_pessoa;
        $setor = $pessoa->getSisrhSetores()->where(['eh_nucleo_academico' => 1])->one();
        $model->id_setor = ($setor == null) ? null : $setor->id_setor;
        $model->situacao = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $tab = $model->tipo_projeto == $model::PESQUISA ? 1 : 2;
            return $this->redirect(['update', 'id' => $model->id_projeto, 'tab' => $tab]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SisapeProjeto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $tab = 0)
    {
        $model = $this->findModel($id);

        if(!Yii::$app->user->can('sisapeAdministrar') && $model->id_pessoa != Yii::$app->session->get('siscat_pessoa')->id_pessoa)
            throw new ForbiddenHttpException('Você não está autorizado a acessar esta página.');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            switch($tab){
                case 0:
                    $tab = $model->tipo_projeto == $model::PESQUISA ? 1 : 2;
                break;
                case 1:
                    $tab = 2;
            }
            return $this->redirect(['update', 'id' => $model->id_projeto, 'tab' => $tab]);
        }

        return $this->render('update', [
            'model' => $model,
            'tab' => $tab,
        ]);
    }

    /**
     * Deletes an existing SisapeProjeto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if($model->situacao >= 1 || $model->id_pessoa != Yii::$app->session->get('siscat_pessoa')->id_pessoa)
            throw new ForbiddenHttpException('Você não está autorizado a acessar esta página.');
        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionPdf($id)
    {
        $model = $this->findModel($id);

        return $this->renderPartial('_pdf', [
            'model' => $model,
        ]);
    }

    public function actionSubmeter($id)
    {
        $model = $this->findModel($id);

        if($model->id_pessoa != Yii::$app->session->get('siscat_pessoa')->id_pessoa || !$model->isEditable())
            throw new ForbiddenHttpException('Você não está autorizado a acessar esta página.');

        $mensagem = 'Não foi possível realizar esta operação:';
        $sucesso = true;

        if($model->tipo_projeto == $model::EXTENSAO && !$model->tipo_extensao){
            $sucesso = false;
            $mensagem .= '<br />- É necessário informar Tipo de Extensão na aba Informações Básicas!';
        }
        if(!SisapeFinanciamento::find()->where(['id_projeto' => $id])->exists()){
            $sucesso = false;
            $mensagem .= '<br />- É necessário informar Origem dos recursos financeiros na aba Financiamento!';
        }
        if(!SisapeProjetoIntegrante::find()->where(['id_projeto' => $id, 'id_pessoa' => $model->id_pessoa])->exists()){
            $sucesso = false;
            $mensagem .= '<br />- É necessário cadastrar o(a) coordenador(a) como membro do projeto na aba Equipe Executora!';
        }
        if(!SisapeAtividade::find()->where(['id_projeto' => $id])->exists()){
            $sucesso = false;
            $mensagem .= '<br />- É necessário informar as atividades do projeto na aba Cronograma!';
        }

        if($sucesso){
            $model->situacao = 16;
            $model->save();
            /* Removida as avaliações de núcleo e copex.
            $parecer = false;
            switch($model->situacao){
                case 0:
                    if($model->id_setor == null)
                        $model->situacao = 15;
                break;
                case 4:
                    $parecer = SisapeParecer::find()->where([
                        'id_projeto' => $model->id_projeto,
                        'atual' => 1,
                        'tipo_parecerista' => SisapeParecer::PARECERISTA_NUCLEO,
                        ])->one();
                break;
                case 9:
                    $parecer = SisapeParecer::find()->where([
                        'id_projeto' => $model->id_projeto,
                        'atual' => 1,
                        'tipo_parecerista' => SisapeParecer::PARECERISTA_COPEX,
                        ])->one();
                break;
            }
            $transaction = Yii::$app->db->beginTransaction();
            try{
                if($model->atualizarSituacao($model->situacao == 1 || $model->situacao == 15, $parecer ? $parecer->id_pessoa : false)){
                    if($parecer && $parecer->parecer != null && strlen(trim($parecer->parecer)) > 0){
                        $parecer->atual = 0;
                        $parecer->save();
                        //se já tiver tido um parecer anterior para essa mesma instância,
                        //cria um novo registro baseado nos dados do anterior
                        $parecer->setIsNewRecord(true);
                        $parecer->id_parecer = null;
                        $parecer->atual = 1;
                        $parecer->parecer = null;
                        $parecer->data = date('Y-m-d');
                        $parecer->save();
                    }
                    $transaction->commit();
                }
            } catch(Exception $t) {
                $transaction->rollBack();
                throw $t;
            }*/
            return $this->redirect(['view', 'id' => $model->id_projeto]);
        }

        return $this->renderAjax('submeter',['mensagem' => $mensagem]);
    }

    public function actionNaohomologar($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('sisapeAdministrar'))
            throw new ForbiddenHttpException('Você não está autorizado a acessar esta página.');
        if($model->situacao == 12 && $model->situacao == 14)
            throw new ForbiddenHttpException('Não é possível realizar essa operação.');
        if ($model->load(Yii::$app->request->post())) {
            $model->situacao = 13;
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id_projeto]);
        }

        return $this->renderAjax('/gerenciamentocopex/aprovarprojeto',[
            'model' => $model, 'parecer' => false,
        ]);
    }

    /**
     * Finds the SisapeProjeto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisapeProjeto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisapeProjeto::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
