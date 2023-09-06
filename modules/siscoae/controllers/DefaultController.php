<?php

namespace app\modules\siscoae\controllers;

use yii\web\Controller;
use Yii;

/**
 * Default controller for the `siscoae` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect(['/siscoae/formulariosocioeconomico']);
        //return $this->render('index');
    }

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction','view' => '//site/error'],
        ];
    }
}
