<?php

namespace app\modules\sisext\controllers;

use Yii;
use yii\web\Controller;
use app\modules\sisext\models\SisapeForm;
use app\modules\sisape\models\SisapeProjeto;

/**
 * Sisape controller for the `sisext` module
 */
class SisapeController extends Controller
{
    public $moduloId = 'sisape';
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SisapeForm();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = SisapeProjeto::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
