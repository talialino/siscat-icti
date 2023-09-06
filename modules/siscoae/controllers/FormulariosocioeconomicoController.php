<?php

namespace app\modules\siscoae\controllers;

use app\modules\siscoae\models\FormularioSocioeconomicoForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class FormulariosocioeconomicoController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new FormularioSocioeconomicoForm();
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->session->set('siscat_formulario_socioeconomico',$model);
        }
        return $this->render('index', ['model' => $model]);
    }

    public function actionFormulariosocioeconomico($pagina = 1)
    {
        $model = Yii::$app->session->get('siscat_formulario_socioeconomico',false);
        if(!$model)
            throw new NotFoundHttpException('Página não encontrada');
        return $this->renderAjax('formulariosocioeconomico', ['model' => $model->csvToArray($pagina + 1)]);
    }

    public function actionUploadcsv()
    {
        $model = new FormularioSocioeconomicoForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->arquivoCsv = UploadedFile::getInstance($model, 'arquivoCsv');
            
            if($model->upload()) {
                return $this->render('uploadcsv', ['mensagem' => 'Arquivo carregado com sucesso!']);
            }
            return $this->render('uploadcsv', ['mensagem' => 'Ocorreu um erro ao salvar arquivo. '.$model->arquivoCsv->error]);
        }
        return $this->render('uploadcsv', ['model' => $model, 'mensagem' => false]);
    }
}