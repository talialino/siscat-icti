<?php

namespace app\modules\sisape\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sisape\models\SisapeProjetoIntegrante;
use app\modules\sisape\models\SisapeIntegranteExterno;
use app\modules\sisape\models\ProjetoIntegranteForm;
use app\modules\sisape\models\SisapeProjetoIntegranteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * SisapeprojetointegranteController implements the CRUD actions for SisapeProjetoIntegrante model.
 */
class SisapeprojetointegranteController extends Controller
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
                        'allow' => Yii::$app->user->can('sisape'),
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
     * Lists all SisapeProjetoIntegrante models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SisapeProjetoIntegranteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SisapeProjetoIntegrante model.
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
     * Creates a new ProjetoIntegranteForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new ProjetoIntegranteForm();
        $model->id_projeto = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/sisape/sisapeprojeto/update', 'id' => $model->id_projeto, 'tab' => 3]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionValidate($id)
    {
        $model = new ProjetoIntegranteForm();
        $model->id_projeto = $id;

        if ($model->load(Yii::$app->request->post())) {
        
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * Updates an existing SisapeProjetoIntegrante model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $projeto = $this->findModel($id);
        $model = new ProjetoIntegranteForm();
        $model->carregarProjetoIntegrante($projeto);

        if ($model->load(Yii::$app->request->post()) && $model->save($projeto)) {
            return $this->redirect(['/sisape/sisapeprojeto/update', 'id' => $model->id_projeto, 'tab' => 3]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SisapeProjetoIntegrante model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $id_projeto = $model->id_projeto;
        $model->delete();

        return $this->redirect(['/sisape/sisapeprojeto/update', 'id' => $model->id_projeto, 'tab' => 3]);
    }

    /**
     * Finds the SisapeProjetoIntegrante model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisapeProjetoIntegrante the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisapeProjetoIntegrante::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
