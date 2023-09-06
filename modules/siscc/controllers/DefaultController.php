<?php

namespace app\modules\siscc\controllers;

use yii\web\Controller;

/**
 * Default controller for the `siscc` module
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
        return $this->redirect([\Yii::$app->user->can('sisccDocente') ? 
            '/siscc/sisccprogramacomponentecurricularpessoa' :
            '/siscc/sisccprogramacomponentecurricular/visualizarprogramas']);
    }

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction','view' => '//site/error'],
        ];
    }
}
