<?php

namespace app\modules\sisape\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sisape\models\SisapeRelatorio;
use app\modules\sisape\models\SisapeParecer;
use app\modules\sisape\models\SisapeProjeto;
use app\modules\sisape\models\SisapeRelatorioSearch;
use app\modules\sisape\models\SisapeProjetoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use Exception;

/**
 * SisaperelatorioController implements the CRUD actions for SisapeRelatorio model.
 */
class SisaperelatorioController extends Controller
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
     * Lists all SisapeRelatorio models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SisapeRelatorioSearch();
        $searchModel->id_pessoa = Yii::$app->session->get('siscat_pessoa')->id_pessoa;
        $searchProjeto = new SisapeProjetoSearch();
        $searchProjeto->id_pessoa = $searchModel->id_pessoa;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchProjeto' => $searchProjeto,
        ]);
    }

    /**
     * Displays a single SisapeRelatorio model.
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
     * Creates a new SisapeRelatorio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SisapeRelatorio();

        $model->situacao = 13;
        $model->data_relatorio = date('Y-m-d');
        
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try{
                if($model->atualizarSituacao(true)){
                    $projeto = $model->projeto;
                    $projeto->situacao = 14;
                    $projeto->save();
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id_relatorio]);
                }
                $transaction->rollBack();
            } catch(Exception $t) {
                $transaction->rollBack();
                throw $t;
            }
        }

        $searchProjeto = new SisapeProjetoSearch();
        $searchProjeto->id_pessoa = Yii::$app->session->get('siscat_pessoa')->id_pessoa;

        return $this->render('create', [
            'model' => $model,
            'searchProjeto' => $searchProjeto,
        ]);
    }

    /**
     * Updates an existing SisapeRelatorio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * O parâmetro $admin, serve para indicar se a atualização está sendo feita por
     * administradores do sisape e não pelo coordenador do projeto
     * @param integer $id
     * @param boolean $admin
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $admin = false)
    {
        $model = $this->findModel($id);
        
        //verifica se o acesso é feito pelos administradores
        if($admin && Yii::$app->user->can('sisapeAdministrar')){
            if ($model->load(Yii::$app->request->post()) && $model->save()){
                return $this->redirect(['view', 'id' => $model->id_relatorio]);
            }
            $searchProjeto = new SisapeProjetoSearch();
            $searchProjeto->id_pessoa = $model->projeto->id_pessoa;

            return $this->render('update', [
                'model' => $model,
                'searchProjeto' => $searchProjeto,
            ]);
        }
        
        $id_pessoa = Yii::$app->session->get('siscat_pessoa')->id_pessoa;

        if($model->pessoa->id_pessoa != $id_pessoa || !$model->isEditable())
            throw new ForbiddenHttpException('Você não está autorizado a acessar esta página.');

        if ($model->load(Yii::$app->request->post())) {
            switch($model->situacao){
                case 1:
                case 13:
                    $model->save();
                break;
                case 4:
                case 9:
                    $parecer = SisapeParecer::find()->where([
                        'id_relatorio' => $model->id_relatorio,
                        'atual' => 1,
                        'tipo_parecerista' => $model->situacao == 4 ? SisapeParecer::PARECERISTA_NUCLEO : SisapeParecer::PARECERISTA_COPEX,
                    ])->one();
                    $transaction = Yii::$app->db->beginTransaction();
                    try{
                        if($model->atualizarSituacao(false, $parecer->id_pessoa)){
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
                break;
            }
            return $this->redirect(['view', 'id' => $model->id_relatorio]);
        }

        $searchProjeto = new SisapeProjetoSearch();
        $searchProjeto->id_pessoa = $id_pessoa;

        return $this->render('update', [
            'model' => $model,
            'searchProjeto' => $searchProjeto,
        ]);
    }

    /**
     * Deletes an existing SisapeRelatorio model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $relatorio = $this->findModel($id);
        $id_projeto = $relatorio->id_projeto;
        $transaction = Yii::$app->db->beginTransaction();

        try{
            $relatorio->delete();

            //Se não tiver mais nenhum relatório referente ao projeto, altera a situação do projeto de relatório apresentado para somente homologado
            if(!SisapeRelatorio::find()->where(['id_projeto'=>$id_projeto])->exists())
            {
                $projeto = SisapeProjeto::findOne($id_projeto);
                if($projeto->situacao == 14){
                    $projeto->situacao = 12;
                    $projeto->save(false);
                }
            }
            $transaction->commit();
        }
        catch(Exception $e)
        {
            $transaction->rollBack();
            throw new yii\web\ServerErrorHttpException("Ocorreu um erro ao executar esta ação. Favor tentar novamente mais tarde!");
        }

        return $this->redirect(['index']);
    }

    public function actionPdf($id)
    {
        $model = $this->findModel($id);

        return $this->renderPartial('_pdf', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the SisapeRelatorio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisapeRelatorio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisapeRelatorio::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
