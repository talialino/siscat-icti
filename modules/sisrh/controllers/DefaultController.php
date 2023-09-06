<?php

namespace app\modules\sisrh\controllers;

use yii\web\Controller;

/**
 * Default controller for the `sisrh` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        //return $this->render('index');
        return $this->redirect('sisrh/reports/contatos');
    }

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction','view' => '//site/error'],
        ];
    }
}
