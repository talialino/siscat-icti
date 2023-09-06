<?php

namespace app\modules\sisai\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sisai\models\SisaiAluno;
use app\modules\sisai\models\SisaiAlunoSearch;
use dektrium\user\models\User;
use Throwable;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SisaialunoController implements the CRUD actions for SisaiAluno model.
 */
class SisaialunoController extends Controller
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
                        'allow' => Yii::$app->user->can('sisaiAlunoGerenciar'),
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
     * Lists all SisaiAluno models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SisaiAlunoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SisaiAluno model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SisaiAluno model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SisaiAluno();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_aluno]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SisaiAluno model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->id_user = $this->editUser($model->id_user);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SisaiAluno model.
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
     * Cria/atualiza o registro da tabela user associado a este SisrhPessoa
     */
    protected function editUser($id)
    {
        $model = $id ? User::findOne($id) : new User();

        $model->load(Yii::$app->request->post());

        $model->email = "$model->username@ufba.br";

        if($model->isNewRecord)
        {
            $model->password_hash = "n";
            $model->auth_key = "n";
            $model->created_at = time();
        }
        $model->updated_at = time();
        if(!$model->save())
            return null;

        if(!$id)
        {
            $result = Yii::$app->db->createCommand()->insert('auth_assignment', [
                'item_name' => 'Discente',
                'user_id' => $model->id,
                'created_at' => time()
            ])->execute();
        }

        return $model->id;
    }

    /**
     * Finds the SisaiAluno model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisaiAluno the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisaiAluno::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
