<?php

namespace app\modules\sisrh\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisrh\models\SisrhPessoaSearch;
use app\modules\sisrh\models\SisrhCargo;
use app\modules\sisrh\models\SisrhAfastamento;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use \dektrium\user\models\User;
/**
 * SisrhpessoaController implements the CRUD actions for SisrhPessoa model.
 */
class SisrhpessoaController extends Controller
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
                        'actions' => ['person'],
                        'allow' => true,
                    ],
                    [
                        'allow' => Yii::$app->user->can('sisrhpessoa'),
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
     * Lists all SisrhPessoa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SisrhPessoaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SisrhPessoa model.
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
     * Creates a new SisrhPessoa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SisrhPessoa();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->id_user = $this->editUser();
            if($model->id_user > 0)
                $model->save();
            return $this->redirect(['update', 'id' => $model->id_pessoa]);
        }

        return $this->render('create', [
            'model' => $model,
            'ocorrencia' => false,
        ]);
    }

    /**
     * Updates an existing SisrhPessoa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $ocorrencia = false)
    {
        $model = $this->findModel($id);

        $model->id_user = $this->editUser($model->id_user);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(isset($model->id_user))
                $this->editRole($model->id_user, $model->id_cargo);
            return $this->redirect(['view', 'id' => $model->id_pessoa]);
        }

        return $this->render('update', [
            'model' => $model,
            'ocorrencia' => $ocorrencia,
        ]);
    }

    /**
     * Deletes an existing SisrhPessoa model.
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
     * Displays a single SisrhPessoa model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPerson()
    {
        return $this->render('person', [
            'model' => SisrhPessoa::find()->where(['id_user' => Yii::$app->user->id])->one(),
        ]);
    }

    public function actionOcorrencia($id)
    {
        $model = new SisrhAfastamento();
        $model->id_pessoa = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['sisrhpessoa/update',
                'id' => $model->id_pessoa,'ocorrencia' => true]);
        } elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_ocorrencia', [
                'model' => $model,
            ]);
        } else {
            return $this->render('_ocorrencia', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the SisrhPessoa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisrhPessoa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisrhPessoa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app','The requested page does not exist.'));
    }

    /**
     * Cria/atualiza o registro da tabela user associado a este SisrhPessoa
     */
    protected function editUser($id = 0)
    {
        $model = $id == 0 ? new User() : User::findOne($id);

        $model->load(Yii::$app->request->post());

        $model->email = "$model->username@ufba.br";

        if($model->isNewRecord)
        {
            $model->password_hash = "n";
            $model->auth_key = "n";
            $model->created_at = time();
        }
        $model->updated_at = time();

        return $model->save() ? $model->id : null;
    }

    /**
     * Define o papel (docente ou técnico) para o user associado a este SisrhPessoa
     */
    protected function editRole($id_user, $id_cargo)
    {
        $categorias = [1 => 'Docente', 2 => 'Tecnico'];
        if(is_numeric($id_user) && is_numeric($id_cargo))
        {
            $categoria = SisrhCargo::find()->select('id_categoria')->where(['id_cargo' => $id_cargo])->one()->id_categoria;

            $query = new Query();

            $row = $query->select('item_name, user_id')->from('auth_assignment')->where(['item_name' => $categorias, 'user_id' => $id_user])->one();

            if(!$row){
                Yii::$app->db->createCommand()->insert('auth_assignment', [
                    'item_name' => $categorias[$categoria],
                    'user_id' => $id_user,
                    'created_at' => time()
                ])->execute();
            } elseif($row['item_name'] != $categorias[$categoria])
            {
                Yii::$app->db->createCommand()->update('auth_assignment', [
                    'item_name' => $categorias[$categoria]],[
                    'user_id' => $id_user,'item_name' => $row['item_name']
                ])->execute();
            }
        }
    }
}
