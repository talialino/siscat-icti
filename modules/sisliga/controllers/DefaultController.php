<?php

namespace app\modules\sisliga\controllers;

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
        if(!file_exists('uploads/sisliga'))
            $this->criarEstruturaPastasUpload();

        return $this->redirect(['/sisliga/sisligaligaacademica']);
        
        // return $this->render('index');
    }

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction','view' => '//site/error'],
        ];
    }

    public function criarEstruturaPastasUpload()
    {
        mkdir('uploads');
        mkdir('uploads/sisliga');
        mkdir('uploads/sisliga/regimento');
        mkdir('uploads/sisliga/solicitacao');
    }
}
