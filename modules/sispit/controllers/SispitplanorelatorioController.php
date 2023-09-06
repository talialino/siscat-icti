<?php

namespace app\modules\sispit\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sispit\models\SispitPlanoRelatorio;
use app\modules\sispit\models\SispitPlanoRelatorioSuplementar;
use app\modules\sispit\models\SispitPlanoRelatorioSearch;
use app\modules\sispit\models\SispitEnsinoComponenteSearch;
use app\modules\sispit\models\SispitAtividadeComplementar;
use app\modules\sispit\models\SispitAtividadeComplementarSuplementar;
use app\modules\sispit\models\SispitOrientacaoAcademicaSearch;
use app\modules\sispit\models\SispitMonitoriaSearch;
use app\modules\sispit\models\SispitEnsinoOrientacaoSearch;
use app\modules\sispit\models\SispitParecer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use app\modules\sispit\helpers\Nucleo;
use app\modules\sispit\models\SispitLigaAcademicaSearch;

/**
 * SispitplanorelatorioController implements the CRUD actions for SispitPlanoRelatorio model.
 */
class SispitplanorelatorioController extends Controller
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
                        'allow' => Yii::$app->user->can('sispit'),
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
     * Lists all SispitPlanoRelatorio models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!Yii::$app->user->can('sispitAdministrar'))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');
        $searchModel = new SispitPlanoRelatorioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SispitPlanoRelatorio model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $pit_rit = null)
    {
        $model = $this->findModel($id);

        if($pit_rit === null)
            $pit_rit = $model->isRitAvailable() ? 1 : 0;

        if(!Yii::$app->user->can('sispitVisualizar',['id' => $model->user_id]))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        if(!$pit_rit && $model->isRitAvailable())
            return $this->render('viewpithomologado', ['model' => $model]);
        
        return $this->render('view', ['model' => $model, 'pit_rit' => $pit_rit]);
    }

    /**
     * Creates a new SispitPlanoRelatorio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!Yii::$app->user->can('sispitDocente',['id' => Yii::$app->user->id]))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');
        $session = Yii::$app->session;

        $ano = $session->get('siscat_ano') ? $session->get('siscat_ano') : false;

        if(!$ano)
            throw new ForbiddenHttpException('Você precisa escolher o ano antes de criar o PIT.');

        $model = SispitPlanoRelatorio::getPlanoRelatorio(Yii::$app->user->id, $ano->id_ano);

        if($model === null)
        {
            $model = new SispitPlanoRelatorio();
            $model->user_id = Yii::$app->user->id;
            $model->id_ano = $ano->id_ano;
            $model->status = 0;
        }
        else
        {
            if($model->status == 9)
                $model->status = 10;
            else
                throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');
        }

        if ($model->save()) {
            return $this->redirect(['ensino', 'id' => $model->id_plano_relatorio]);
        }
    }

    /**
     * Updates an existing SispitPlanoRelatorio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->isEditable())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        if($model->isRitAvailable())
            $model->data_preenchimento_rit = date('Y-m-d');
        else
            $model->data_preenchimento_pit = date('Y-m-d');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_plano_relatorio]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SispitPlanoRelatorio model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(!Yii::$app->user->can('sispitGerenciar'))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionEnsino($id)
    {
        
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('sispitGerenciar',['id' => $model->user_id]))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');
        
        $pit_rit = $model->isRitAvailable()? 1 : 0;

        $ensinoComponente = new SispitEnsinoComponenteSearch();
        $ensinoComponente->id_plano_relatorio = $id;
        $ensinoComponente->pit_rit = $pit_rit;
        
        $suplementar = Yii::$app->session->get('siscat_ano')->suplementar;
        $atividadeComplementar = $suplementar ? SispitAtividadeComplementarSuplementar::findOne($id) : SispitAtividadeComplementar::findOne($id);
        if($atividadeComplementar === null)
        {
            $atividadeComplementar = $suplementar ? new SispitAtividadeComplementarSuplementar() : new SispitAtividadeComplementarSuplementar();
            $atividadeComplementar->id_plano_relatorio = $id;
            $atividadeComplementar->scenario = $pit_rit ? SispitAtividadeComplementar::SCENARIO_ATIVIDADE_RIT : SispitAtividadeComplementar::SCENARIO_ATIVIDADE_PIT;
            $atividadeComplementar->save();
        }

        return $this->render('ensino', [
            'model' => $model,
            'ensinoComponente' => $ensinoComponente,
            'atividadeComplementar' => $atividadeComplementar,
        ]);
    }

    public function actionOrientacao($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('sispitGerenciar',['id' => $model->user_id]))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        $pit_rit = $model->isRitAvailable()? 1 : 0;
        
        $orientacaoAcademica = new SispitOrientacaoAcademicaSearch;
        $orientacaoAcademica->id_plano_relatorio = $id;
        $orientacaoAcademica->pit_rit = $pit_rit;

        $monitoria = new SispitMonitoriaSearch;
        $monitoria->id_plano_relatorio = $id;
        $monitoria->pit_rit = $pit_rit;
        
        $ensinoOrientacao = new SispitEnsinoOrientacaoSearch;
        $ensinoOrientacao->id_plano_relatorio = $id;
        $ensinoOrientacao->pit_rit = $pit_rit;

        $ligaAcademica = new SispitLigaAcademicaSearch();
        $ligaAcademica->id_plano_relatorio = $id;
        $ligaAcademica->pit_rit = $pit_rit;

        $suplementar = Yii::$app->session->get('siscat_ano')->suplementar;
        $atividadeComplementar = $suplementar ? SispitAtividadeComplementarSuplementar::findOne($id) : SispitAtividadeComplementar::findOne($id);
        $atividadeComplementar->scenario = $pit_rit ? SispitAtividadeComplementar::SCENARIO_ORIENTACAO_RIT : SispitAtividadeComplementar::SCENARIO_ORIENTACAO_PIT;

        return $this->render('orientacao', [
            'model' => $model,
            'orientacaoAcademica' => $orientacaoAcademica,
            'monitoria' => $monitoria,
            'ensinoOrientacao' => $ensinoOrientacao,
            'ligaAcademica' => $ligaAcademica,
            'atividadeComplementar' => $atividadeComplementar,
        ]);
    }

    /**
     * Updates an existing SispitPlanoRelatorio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionObservacoes($id)
    {
        $model = $this->findModel($id);

        if(!Yii::$app->user->can('sispitGerenciar',['id' => $model->user_id]))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_plano_relatorio]);
        }

        return $this->render('observacoes', [
            'model' => $model,
        ]);
    }

    /**
     * Submete o pit ou rit para ser avaliado pelo parecerista
     */
    public function actionSubmeter($id)
    {
        $model = $this->findModel($id);
        if(!$model->isEditable())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');
        if(!$model->validarCargaHoraria())
            throw new ForbiddenHttpException('Você precisa remover as pendências antes de realizar esta operação.');
        
        $parecer = false;
        switch($model->status){
            case 4:
                $parecer = SispitParecer::find()->where([
                    'id_plano_relatorio' => $model->id_plano_relatorio,
                    'atual' => 1,
                    'tipo_parecerista' => SispitParecer::PARECERISTA_NUCLEO,
                    'pit_rit' => 0
                    ])->one();
            break;
            case 8:
                $parecer = SispitParecer::find()->where([
                    'id_plano_relatorio' => $model->id_plano_relatorio,
                    'atual' => 1,
                    'tipo_parecerista' => SispitParecer::PARECERISTA_CAC,
                    'pit_rit' => 0
                    ])->one();
            break;
            case 14:
                $parecer = SispitParecer::find()->where([
                    'id_plano_relatorio' => $model->id_plano_relatorio,
                    'atual' => 1,
                    'tipo_parecerista' => SispitParecer::PARECERISTA_NUCLEO,
                    'pit_rit' => 1
                    ])->one();
            break;
            case 18:
                $parecer = SispitParecer::find()->where([
                    'id_plano_relatorio' => $model->id_plano_relatorio,
                    'atual' => 1,
                    'tipo_parecerista' => SispitParecer::PARECERISTA_CAC,
                    'pit_rit' => 1
                    ])->one();
        }

        $transaction = Yii::$app->db->beginTransaction();

        try{
            if($model->atualizarStatus(false, $parecer ? $parecer->id_pessoa : false) && $parecer){
                $parecer->atual = 0;
                $parecer->save();
                //se já tiver tido um parecer anterior para essa mesma instância,
                //cria um novo registro baseado nos dados do anterior
                $parecer->setIsNewRecord(true);
                $parecer->id_parecer = null;
                $parecer->atual = 1;
                $parecer->parecer = null;
                $parecer->comentario = null;
                $parecer->save();
            }
            $transaction->commit();
        }
        catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $t) {
            $transaction->rollBack();
            throw $t;
        }

        return $this->redirect(['view', 'id' => $model->id_plano_relatorio]);
    }

    public function actionEditarnovamente($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('sispitGerenciar',['id' => $model->user_id]) || ($model->status % 10 != 1))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');
        $model->status--;
        $model->save();

        return $this->redirect(['view', 'id' => $model->id_plano_relatorio]);
    }

    /**
     * Finds the SispitPlanoRelatorio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SispitPlanoRelatorio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $ano = Yii::$app->session->get('siscat_ano');
        if (($model = ($ano->suplementar ? SispitPlanoRelatorioSuplementar::findOne($id) : SispitPlanoRelatorio::findOne($id))) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Página não encontrada.');
    }
    public function actionPdf($id, $pit_rit = null) {

        $model = $this->findModel($id);

        if($pit_rit === null)
            $pit_rit = $model->isRitAvailable() ? 1 : 0;

        if(!Yii::$app->user->can('sispitVisualizar',['id' => $model->user_id]) &&
            !(Yii::$app->user->can('sispitGerenciarNucleo') &&
            Nucleo::validarNucleoPlanoRelatorio($model->id_plano_relatorio,Yii::$app->session->get('siscat_nucleo'))))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        // get your HTML raw content without any layouts or scripts
        return $this->renderPartial('pdf', ['model' => $model, 'pit_rit' => $pit_rit]);
    }

    /**
     * Docente em estágio probatório pode submeter um rit parcial do 1º semestre
     */
    public function actionSubmeterritparcial($id)
    {
        $model = $this->findModel($id);
        if(!$model->exibirBotaoSubmeterRitParcial())
            throw new ForbiddenHttpException('Você não pode realizar esta operação.');
        
        if($model->situacao_estagio_probatorio == 1)
        {
            $model->status = 15;
            $model->data_preenchimento_rit = date('Y-m-d');
            $model->atualizarStatus(true);
        }
        return $this->redirect(['view', 'id' => $model->id_plano_relatorio]);
    }
}
