<?php

namespace app\controllers;

use Yii;
use app\models\AuthItemSisrhSetor;
use app\models\AuthItemSisrhSetorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthitemsisrhsetorController implements the CRUD actions for AuthItemSisrhSetor model.
 */
class AuthitemsisrhsetorController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthItemSisrhSetor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemSisrhSetorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthItemSisrhSetor model.
     * @param string $name
     * @param integer $id_setor
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
     * Creates a new AuthItemSisrhSetor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItemSisrhSetor();

        if ($model->load(Yii::$app->request->post())) {
            if($model->id_comissao)
                $model->funcao = $_POST['funcaoComissao'];
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AuthItemSisrhSetor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $name
     * @param integer $id_setor
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->id_comissao)
                $model->funcao = $_POST['funcaoComissao'];
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AuthItemSisrhSetor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $name
     * @param integer $id_setor
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthItemSisrhSetor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $name
     * @param integer $id_setor
     * @return AuthItemSisrhSetor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthItemSisrhSetor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
