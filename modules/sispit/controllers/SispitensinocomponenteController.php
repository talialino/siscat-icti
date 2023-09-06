<?php

namespace app\modules\sispit\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sispit\models\SispitEnsinoComponente;
use app\modules\sispit\models\SispitEnsinoComponenteSearch;
use app\modules\sispit\models\SispitPlanoRelatorio;
use app\modules\siscc\models\SisccComponenteCurricular;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

/**
 * SispitensinocomponenteController implements the CRUD actions for SispitEnsinoComponente model.
 */
class SispitensinocomponenteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => Yii::$app->user->can('sispit'),
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SispitEnsinoComponente models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SispitEnsinoComponenteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SispitEnsinoComponente model.
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
     * Creates a new SispitEnsinoComponente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new SispitEnsinoComponente();
        $model->id_plano_relatorio = $id;

        $plano = SispitPlanoRelatorio::findOne($id);

        if(!$plano->isEditable())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');


        if ($model->load(Yii::$app->request->post())){
            $model->pit_rit = $plano->isRitEditable() ? 1 : 0;

            if($model->save())
                return $this->redirect(['sispitplanorelatorio/ensino', 'id' => $model->id_plano_relatorio]);
        }
        
        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SispitEnsinoComponente model..
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $plano = $model->planoRelatorio;

        if(!$plano->isEditable())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        if ($model->load(Yii::$app->request->post())){
            $model->pit_rit = $plano->isRitEditable() ? 1 : 0;

            if($model->save())
                return $this->redirect(['sispitplanorelatorio/ensino', 'id' => $model->id_plano_relatorio]);
        }
        
        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

   /* public function actionValidate($id, $plano = false)
    {
        $model = $plano ? new SispitEnsinoComponente() : $this->findModel($id);

        if($plano)
            $model->id_plano_relatorio = $id;

        if ($model->load(Yii::$app->request->post())) {
            
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }*/

    /**
     * Deletes an existing SispitEnsinoComponente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if(!$model->planoRelatorio->isEditable())
            throw new ForbiddenHttpException('Você não está autorizado a realizar esta operação.');
        $model->delete();

        return $this->redirect(['sispitplanorelatorio/ensino', 'id' => $model->id_plano_relatorio]);
    }

    public function actionImport($id)
    {
        $plano = SispitPlanoRelatorio::findOne($id);
        $ano = $plano->sispitAno;
        $id_pessoa = $plano->pessoa->id_pessoa;

        if(!$plano->isEditable())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        $componentes = Yii::$app->db->createCommand(
            "SELECT pc.id_componente_curricular, semestre
            FROM siscc_programa_componente_curricular pc, siscc_programa_componente_curricular_pessoa pp, siscc_semestre s
            WHERE s.ano = $ano->ano AND s.id_semestre = pc.id_semestre AND pc.id_programa_componente_curricular = pp.id_programa_componente_curricular AND pp.id_pessoa = $id_pessoa
        ")->queryAll();
        if(count($componentes) == 0)
            return "Nenhum componente foi encontrado!";
        foreach($componentes as $componente){
            $componente['id_plano_relatorio'] = $id;
            $componente['pit_rit'] = $plano->isRitAvailable() ? 1 : 0;
            if($ano->suplementar && $componente['semestre'] == 3){
                $componente['semestre'] = 1;
                if(!SispitEnsinoComponente::find()->where($componente)->exists())
                {
                    $model = new SispitEnsinoComponente();
                    $model->setAttributes($componente);
                    $model->save();
                }
            }
            else if(!$ano->suplementar && $componente['semestre'] != 3){
                if(!SispitEnsinoComponente::find()->where($componente)->exists())
                {
                    $model = new SispitEnsinoComponente();
                    $model->setAttributes($componente);
                    $model->save();
                }
                //Se o componente estiver cadastrado no primeiro semestre e for anual adiciona
                //também ao segundo semestre
                if($componente['semestre'] == 1 && SisccComponenteCurricular::find()->where(
                    ['id_componente_curricular' => $componente['id_componente_curricular'], 'anual' => 1])->exists())
                {
                    $componente['semestre'] = 2;
                    if(!SispitEnsinoComponente::find()->where($componente)->exists())
                    {
                        $model = new SispitEnsinoComponente();
                        $model->setAttributes($componente);
                        $model->save();
                    }
                }
            }
        }
        return $this->redirect(['sispitplanorelatorio/ensino', 'id' => $id]);
    }

    /**
     * Finds the SispitEnsinoComponente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SispitEnsinoComponente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SispitEnsinoComponente::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
