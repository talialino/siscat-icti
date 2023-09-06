<?php

namespace app\modules\siscc\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\siscc\models\SisccProgramaComponenteCurricular;
use app\modules\siscc\models\SisccSemestre;
use app\modules\siscc\models\SisccParecer;
use app\modules\siscc\models\SisccProgramaComponenteCurricularSearch;
use app\modules\siscc\models\SisccProgramaComponenteCurricularPessoa;
use app\modules\siscc\models\SisccProgramaComponenteCurricularBibliografia;
use app\modules\siscc\models\SisccImportarProgramaForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use Exception;

/**
 * SisccprogramacomponentecurricularController implements the CRUD actions for SisccProgramaComponenteCurricular model.
 */
class SisccprogramacomponentecurricularController extends Controller
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
                        'actions' => ['view','pdf','visualizarprogramas'],
                        'allow' => Yii::$app->user->can('siscc'),
                    ],
                    [
                        'actions' => ['editar', 'importarprograma', 'submeter'],
                        'allow' => Yii::$app->user->can('sisccDocente'),
                    ],
                    [
                        'allow' => Yii::$app->user->can('sisccAdministrarVinculo'),
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
     * Lists all SisccProgramaComponenteCurricular models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SisccProgramaComponenteCurricularSearch();
        $searchModel->id_semestre = SisccSemestre::find()->max('id_semestre');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new SisccProgramaComponenteCurricular model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SisccProgramaComponenteCurricular();

        $model->situacao = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SisccProgramaComponenteCurricular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SisccProgramaComponenteCurricular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionEditar($id, $showAbaBibliografia = false)
    {
        $model = $this->findModel($id);
        $pessoa = Yii::$app->session->get('siscat_pessoa');

        if(!$model->isEditable())
            throw new ForbiddenHttpException('Você não está autorizado a acessar esta página.');
        
        if(!SisccProgramaComponenteCurricularPessoa::find()->where([
                'id_programa_componente_curricular' => $id,
                'id_pessoa' => $pessoa->id_pessoa,
            ])->exists() &&
            !SisccParecer::find()->where([
                'id_programa_componente_curricular' => $id,
                'tipo_parecerista' => $model->situacao == 4 ? SisccParecer::PARECERISTA_COLEGIADO :SisccParecer::PARECERISTA_CAC,
                'id_pessoa' => $pessoa->id_pessoa,
                'atual' => 1,
            ])->exists())
            throw new ForbiddenHttpException('Você não está autorizado a acessar esta página.');

        if(!$model->objetivos_especificos)
            $model->objetivos_especificos = '<ul><li>&nbsp;</li></ul>';

        if(!$model->conteudo_programatico)
            $model->conteudo_programatico = '<ul><li>&nbsp;</li></ul>';

        $dataProvider = new ActiveDataProvider([
            'query' => $model->getSisccProgramaComponenteCurricularBibliografias(),
            'pagination' => false,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        $showAbaBibliografia = true;

        /**
         * objetivos_especificos e conteudo_programatico utilizam formato de lista
         * Nas linhas a seguir, caso o campo esteja vazio
         */

        return $this->render('editar', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'showAbaBibliografia' => $showAbaBibliografia,
        ]);
    }

    public function actionSubmeter($id, $id_parecer = false)
    {
        $model = $this->findModel($id);
        $pessoa = Yii::$app->session->get('siscat_pessoa');
        //Se o programa não estiver editável, a função submeter não pode ser executada
        if(!$model->isEditable())
            throw new ForbiddenHttpException('Não é possível submeter um programa que não está disponível para edição.');
        /** 
         * O if a seguir verifica se o docente está vinculado ao componente, ou é parecerista
         * autorizado a fazer modificações ou se é coordenador do colegiado do programa
         * caso não seja nenhuma dessas opções, exibe a informação de que o usuário
         * não está autorizado a realizar esta operação
        */
        
        if(!SisccProgramaComponenteCurricularPessoa::find()->where([
                'id_programa_componente_curricular' => $id,
                'id_pessoa' => $pessoa->id_pessoa,
            ])->exists() &&
            !SisccParecer::find()->where([
                'id_programa_componente_curricular' => $id,
                'tipo_parecerista' => $model->situacao == 4 ? SisccParecer::PARECERISTA_COLEGIADO :SisccParecer::PARECERISTA_CAC,
                'id_pessoa' => $pessoa->id_pessoa,
                'atual' => 1,
            ])->exists() &&
            !$pessoa->getSisrhSetorPessoas()->where([
                'id_setor' => $model->id_setor])->andWhere(['<','funcao',2])->exists())
            throw new ForbiddenHttpException('Você não está autorizado a realizar esta operação.');

        $parecer = false;
        switch($model->situacao){
            case 4:
                $parecer = SisccParecer::find()->where([
                    'id_programa_componente_curricular' => $model->id_programa_componente_curricular,
                    'atual' => 1,
                    'tipo_parecerista' => SisccParecer::PARECERISTA_COLEGIADO,
                    ])->one();
            break;
            case 9:
                $parecer = SisccParecer::find()->where([
                    'id_programa_componente_curricular' => $model->id_programa_componente_curricular,
                    'atual' => 1,
                    'tipo_parecerista' => SisccParecer::PARECERISTA_CAC,
                    ])->one();
            break;
        }
            $transaction = Yii::$app->db->beginTransaction();
            try{
                if($model->atualizarSituacao($model->situacao == 1, $parecer ? $parecer->id_pessoa : false)){
                    if($parecer && $parecer->parecer != null && strlen(trim($parecer->parecer)) > 0){
                        if($id_parecer && $id_parecer == $parecer->id_parecer)
                            $parecer->load(Yii::$app->request->post());
                        $parecer->atual = 0;
                        $parecer->save();
                        //se já tiver tido um parecer anterior para essa mesma instância,
                        //cria um novo registro baseado nos dados do anterior
                        $parecer->setIsNewRecord(true);
                        $parecer->id_parecer = null;
                        $parecer->atual = 1;
                        $parecer->parecer = null;
                        $parecer->comentario = null;
                        $parecer->data = date('Y-m-d');
                        $parecer->save();
                    }
                    $transaction->commit();
                }
            } catch(Exception $t) {
                $transaction->rollBack();
                throw $t; 
            }
            return $this->redirect(['view', 'id' => $model->id_programa_componente_curricular]);
    }

    public function actionPdf($id)
    {
        $model = $this->findModel($id);

        return $this->renderPartial('_pdf', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionImportarsemestre($id)
    {
        $semestre = SisccSemestre::findOne($id);

        $model = new SisccSemestre();

        if ($model->load(Yii::$app->request->post())){
            
            $programas = $model->sisccProgramaComponenteCurriculars;
            
            $transaction = $model->getDb()->beginTransaction();
            try{
                foreach($programas as $programa)
                {
                    $pessoas = $programa->sisccProgramaComponenteCurricularPessoas;
                    $novoPrograma = new SisccProgramaComponenteCurricular();
                    $novoPrograma->id_semestre = $id;
                    $novoPrograma->id_componente_curricular = $programa->id_componente_curricular;
                    $novoPrograma->id_setor = $programa->id_setor;
                    $novoPrograma->save();
                    foreach($pessoas as $pessoa){
                        $pp = new SisccProgramaComponenteCurricularPessoa();
                        $pp->id_pessoa = $pessoa->id_pessoa;
                        $pp-> id_programa_componente_curricular = $novoPrograma->id_programa_componente_curricular;
                        $pp->save();
                    }
                }
                $transaction->commit();
            
            } catch(Exception $t) {
                $transaction->rollBack();
                throw $t;
            }

            return $this->redirect(['index', 'SisccProgramaComponenteCurricularSearch[id_semestre]' => $id]);
        }
        return $this->renderAjax('importarsemestre', [
            'model' => $model,
            'semestre' => $semestre->semestre,
            'ano' => $semestre->ano,
        ]);
    }

    public function actionImportarprograma($id)
    {
        $programa = SisccProgramaComponenteCurricular::findOne($id);

         if(!$programa->podeImportar())
            return "<div class='alert alert-warning'>A função importar não está disponível para este componente.</div>";
        $model = new SisccImportarProgramaForm();

        if($model->load(Yii::$app->request->post()))
        {
            $transaction = $programa->getDb()->beginTransaction();
            try{
                $importado = SisccProgramaComponenteCurricular::findOne($model->id_programa_componente_curricular);
            
                $programa->objetivo_geral = $importado->objetivo_geral;
                $programa->objetivos_especificos = $importado->objetivos_especificos;
                $programa->conteudo_programatico = $importado->conteudo_programatico;
                                
                if(!$model->modificacoes && strtotime("$importado->data_aprovacao_coordenacao +5 years") > time()){
                    
                    $programa->data_aprovacao_colegiado = $importado->data_aprovacao_colegiado;
                    $programa->data_aprovacao_coordenacao = $importado->data_aprovacao_coordenacao;
                    $programa->situacao = 11;
                }
                else
                    $programa->situacao = 0;
                $programa->save();
                foreach($importado->sisccProgramaComponenteCurricularBibliografias as $bibliografia){
                    $pb = new SisccProgramaComponenteCurricularBibliografia();
                    $pb->id_bibliografia = $bibliografia->id_bibliografia;
                    $pb->id_programa_componente_curricular = $programa->id_programa_componente_curricular;
                    $pb->tipo_referencia = $bibliografia->tipo_referencia;
                    $pb->save();
                }
                $transaction->commit();

                return $this->redirect([ $programa->situacao == 0 ?
                    '/siscc/sisccprogramacomponentecurricular/editar' :
                    '/siscc/sisccprogramacomponentecurricular/view', 'id' => $id]);
            
            } catch(Exception $t) {
                $transaction->rollBack();
                throw $t;
            }
        }

        $semestre = $programa->semestre;
        
        $programas = SisccProgramaComponenteCurricular::find()
            ->innerJoinWith('semestre as semestre')
            ->where(['id_componente_curricular' => $programa->id_componente_curricular,
            'semestre.remoto' => $semestre->remoto,
            'id_setor' => $programa->id_setor, 'situacao' => 11])
            ->andWhere(['>','data_aprovacao_coordenacao', '2019-01-01'])
            ->orderby('data_aprovacao_coordenacao DESC')->limit(1)->all();

        if(count($programas) == 0)
            //throw new NotFoundHttpException("Não existem programas a serem importados.");
            return "<div class='alert alert-warning'>Não existem programas a serem importados.</div>";

        return $this->renderAjax('importarprograma', [
            'model' => $model,
            'programas' => $programas,
        ]);
    }

    /**
     * Lists all SisccProgramaComponenteCurricular models.
     * @return mixed
     */
    public function actionVisualizarprogramas()
    {
        $searchModel = new SisccProgramaComponenteCurricularSearch();
        $searchModel->id_semestre = SisccSemestre::find()->max('id_semestre');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
        return $this->render('visualizarprogramas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Finds the SisccProgramaComponenteCurricular model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisccProgramaComponenteCurricular the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisccProgramaComponenteCurricular::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}