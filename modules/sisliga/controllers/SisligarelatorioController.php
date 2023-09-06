<?php

namespace app\modules\sisliga\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sisliga\models\SisligaRelatorio;
use app\modules\sisliga\models\SisligaParecer;
use app\modules\sisliga\models\SisligaRelatorioSearch;
use app\modules\sisliga\models\SisligaLigaAcademicaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use Exception;

/**
 * SisligarelatorioController implements the CRUD actions for SisligaRelatorio model.
 */
class SisligarelatorioController extends Controller
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
     * Lists all SisligaRelatorio models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SisligaRelatorioSearch();
        $searchModel->id_pessoa = Yii::$app->session->get('siscat_pessoa')->id_pessoa;
        $searchLiga = new SisligaLigaAcademicaSearch();
        $searchLiga->id_pessoa = $searchModel->id_pessoa;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchLiga' => $searchLiga,
        ]);
    }

    /**
     * Displays a single SisligaRelatorio model.
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
     * Creates a new SisligaRelatorio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SisligaRelatorio();

        $model->situacao = 1;
        $model->data_relatorio = date('Y-m-d');
        
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try{
                if($model->atualizarSituacao(true)){
                    $liga = $model->ligaAcademica;
                    $liga->situacao = 9;
                    $liga->save(false);
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id_relatorio]);
                }
                $transaction->rollBack();
            } catch(Exception $t) {
                $transaction->rollBack();
                throw $t;
            }
        }

        $searchLiga = new SisligaLigaAcademicaSearch();
        $searchLiga->id_pessoa = Yii::$app->session->get('siscat_pessoa')->id_pessoa;

        return $this->render('create', [
            'model' => $model,
            'searchLiga' => $searchLiga,
        ]);
    }

    /**
     * Updates an existing SisligaRelatorio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * O parâmetro $admin, serve para indicar se a atualização está sendo feita por
     * administradores do sisliga e não pelo coordenador da Liga Academica
     * @param integer $id
     * @param boolean $admin
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $admin = false)
    {
        $model = $this->findModel($id);
        
        //verifica se o acesso é feito pelos administradores
        if($admin && Yii::$app->user->can('sisligaAdministrar')){
            if ($model->load(Yii::$app->request->post()) && $model->save()){
                return $this->redirect(['view', 'id' => $model->id_relatorio]);
            }
            $searchLiga = new SisligaLigaAcademicaSearch();
            $searchLiga->id_pessoa = $model->ligaAcademica->id_pessoa;

            return $this->render('update', [
                'model' => $model,
                'searchLiga' => $searchLiga,
            ]);
        }
        
        $id_pessoa = Yii::$app->session->get('siscat_pessoa')->id_pessoa;

        if($model->pessoa->id_pessoa != $id_pessoa || !$model->isEditable())
            throw new ForbiddenHttpException('Você não está autorizado a acessar esta página.');

        if ($model->load(Yii::$app->request->post())) {
            switch($model->situacao){
                case 1:
                    $model->save();
                break;
                case 4:
                    $parecer = SisligaParecer::find()->where([
                        'id_relatorio' => $model->id_relatorio,
                        'atual' => 1,
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

        $searchLiga = new SisligaLigaAcademicaSearch();
        $searchLiga->id_pessoa = $id_pessoa;

        return $this->render('update', [
            'model' => $model,
            'searchLiga' => $searchLiga,
        ]);
    }

    /**
     * Deletes an existing SisligaRelatorio model.
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

    public function actionPdf($id)
    {
        $model = $this->findModel($id);

        return $this->renderPartial('_pdf', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the SisligaRelatorio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisligaRelatorio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisligaRelatorio::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
