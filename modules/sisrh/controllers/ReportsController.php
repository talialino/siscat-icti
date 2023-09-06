<?php

namespace app\modules\sisrh\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisrh\models\SisrhPessoaSearch;
use app\modules\sisrh\models\SisrhCategoriaSearch;
use app\modules\sisrh\models\SisrhAfastamentoSearch;
use app\modules\sisrh\models\SisrhContatosSearch;

class ReportsController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['contatos'],
                        'allow' => Yii::$app->user->can('sisrh'),
                    ],
                    [
                        'allow' => Yii::$app->user->can('sisrhreports'),
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $searchModel = new SisrhPessoaSearch();
        $searchModel->situacao = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionContatos()
    {
        $categoria = new SisrhCategoriaSearch();
        $searchModel = new SisrhContatosSearch();
        $searchModel->situacao = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('contatos', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categoria' => $categoria,
        ]);
    }

    public function actionOcorrencias()
    {
        $searchModel = new SisrhAfastamentoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('ocorrencias', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
