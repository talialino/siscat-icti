<?php

namespace app\modules\sisape\controllers;

use yii\web\Controller;
use Yii;

/**
 * Default controller for the `sisape` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect(['/sisape/sisapeprojeto']);
        // return $this->render('index');
    }

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction','view' => '//site/error'],
        ];
    }
}
