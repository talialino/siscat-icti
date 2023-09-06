<?php

namespace app\modules\sisai\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sisai\models\SisaiProfessorComponenteCurricular;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SisaiprofessorcomponentecurricularController implements the CRUD actions for SisaiProfessorComponenteCurricular model.
 */
class SisaiprofessorcomponentecurricularController extends Controller
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
                        'allow' => Yii::$app->user->can('Docente'),
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
     * Deletes an existing SisaiProfessorComponenteCurricular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['/sisai/sisaiavaliacao/docente']);
    }

    /**
     * Finds the SisaiProfessorComponenteCurricular model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisaiProfessorComponenteCurricular the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisaiProfessorComponenteCurricular::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
