<?php

namespace app\modules\sisliga\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sisliga\models\SisligaLigaAcademica;
use app\modules\sisliga\models\SisligaLigaAcademicaSearch;
use app\modules\sisliga\models\SisligaLigaIntegrante;
use app\modules\sisliga\models\SisligaParecer;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * SisligaligaacademicaController implements the CRUD actions for SisligaLigaAcademica model.
 */
class SisligaligaacademicaController extends Controller
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
     * Lists all SisligaLigaAcademica models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SisligaLigaAcademicaSearch();
        $searchModel->id_pessoa = Yii::$app->session->get('siscat_pessoa')->id_pessoa;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SisligaLigaAcademica model.
     * Somente podem visualizar esta página o coordenador da liga acadêmica, coordenação do núcleo a qual a liga acadêmica foi submetido e a copex
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $pessoa = Yii::$app->session->get('siscat_pessoa');

        if($model->id_pessoa != $pessoa->id_pessoa && !Yii::$app->user->can('sisligaAdministrar'))
            if(!$pessoa->getSisrhSetorPessoas()->where(['id_setor' => $model->id_setor])->andWhere(['<','funcao',2])->exists())
                throw new ForbiddenHttpException('Você não está autorizado a acessar esta página.');

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new SisligaLigaAcademica model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SisligaLigaAcademica();

        $pessoa = Yii::$app->session->get('siscat_pessoa');
        if(SisligaLigaAcademica::existePendencia($pessoa->id_pessoa))
            throw new ForbiddenHttpException('Você não pode criar novas ligas acadêmicas pois você possui relatórios pendentes.');
        $model->id_pessoa = $pessoa->id_pessoa;
        $model->situacao = 0;

        if ($model->load(Yii::$app->request->post())) {
            $model->solicitacao = UploadedFile::getInstance($model, 'solicitacao');
            $model->regimento = UploadedFile::getInstance($model, 'regimento');
            
            if($model->upload() && $model->save(false)) {
                return $this->redirect(['update', 'id' => $model->id_liga_academica, 'tab' => 1]);
            }
        }

        $model->scenario = 'create';//solicitação e regimento são obrigatórios somente neste scenario

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SisligaLigaAcademica model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $tab = 0)
    {
        $model = $this->findModel($id);
        

        if(!Yii::$app->user->can('sisligaAdministrar') && $model->id_pessoa != Yii::$app->session->get('siscat_pessoa')->id_pessoa)
            throw new ForbiddenHttpException('Você não está autorizado a acessar esta página.');

        
        $model->solicitacao = $model->arquivo_solicitacao;
        $model->regimento = $model->arquivo_regimento;

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            $tab = 1;

            return $this->redirect(['update', 'id' => $model->id_liga_academica, 'tab' => $tab]);
        }

        $model->scenario='';

        return $this->render('update', [
            'model' => $model,
            'tab' => $tab,
        ]);
    }

    /**
     * Deletes an existing SisligaLigaAcademica model.
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

        //if(!SisligaLigaIntegrante::find()->where(['id_liga_academica' => $id, 'id_pessoa' => $model->id_pessoa])->exists()){
        //    $sucesso = false;
        //    $mensagem .= '<br />- É necessário cadastrar o(a) responsável da liga como membro na aba Equipe Executora!';
        //}

        if($sucesso){
            
            $parecer = false;
            switch($model->situacao){
                case 4:
                    $parecer = SisligaParecer::find()->where([
                        'id_liga_academica' => $model->id_liga_academica,
                        'atual' => 1,
                        ])->one();
                break;
            }
            $transaction = Yii::$app->db->beginTransaction();
            try{
                if($model->atualizarSituacao($model->situacao == 1, $parecer ? $parecer->id_pessoa : false)){
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
            }
            return $this->redirect(['view', 'id' => $model->id_liga_academica]);
        }

        return $this->renderAjax('submeter',['mensagem' => $mensagem]);
    }

    public function actionNaohomologar($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('sisligaAdministrar'))
            throw new ForbiddenHttpException('Você não está autorizado a acessar esta página.');
        if($model->situacao == 7 && $model->situacao == 9)
            throw new ForbiddenHttpException('Não é possível realizar essa operação.');
        if ($model->load(Yii::$app->request->post())) {
            $model->situacao = 8;
            if($model->save(false))
                return $this->redirect(['view', 'id' => $model->id_liga_academica]);
        }

        return $this->renderAjax('/gerenciamento/aprovarliga',[
            'model' => $model, 'parecer' => false,
        ]);
    }

    /**
     * Exibe os arquivos pdf adicionados no cadastro da liga
     * tipoArquivo pode ser:
     * 1 - arquivo de solicitação
     * 2 - arquivo de regimento
     * @param integer $id
     * @param integer $tipoArquivo
     * @return mixed
     */
    public function actionVisualizararquivo($id, $tipoArquivo)
    {
        $model = $this->findModel($id);
        switch($tipoArquivo){
            case 1:
                return Yii::$app->response->sendFile($model->arquivo_solicitacao, "solicitacao_liga_$model->id_liga_academica.pdf", ['inline' => true]);
            case 2:
                return Yii::$app->response->sendFile($model->arquivo_regimento, "regimento_liga_$model->id_liga_academica.pdf", ['inline' => true]);
        }
    }

    public function actionAlterararquivo($id, $tipoArquivo)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())){
            switch($tipoArquivo){
                case 1:
                    $model->solicitacao = UploadedFile::getInstance($model, 'solicitacao');
                case 2:
                    $model->regimento = UploadedFile::getInstance($model, 'regimento');
            }
            if($model->upload($tipoArquivo))
                return 'Eba! Arquivo alterado com sucesso!';
            else
                return 'Ocorreu um erro ao salvar o arquivo!';
        }

        return $this->renderAjax('alterararquivo',['model' => $model, 'tipoArquivo' => $tipoArquivo]);
    }

    /**
     * Finds the SisligaLigaAcademica model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisligaLigaAcademica the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisligaLigaAcademica::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
