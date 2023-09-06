<?php

namespace app\modules\sispit\controllers;

use yii\web\Controller;
use \Yii;
use yii\db\Query;
use app\modules\sispit\models\SispitPlanoRelatorio;
use app\modules\sispit\models\SispitParecer;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `sispit` module
 */
class DefaultController extends Controller
{

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        $user = Yii::$app->user;
        $situacaoplano = false;
        $ano = $session->get('siscat_ano');
        
        if($ano){
            $plano = SispitPlanoRelatorio::getPlanoRelatorio($user->id, $ano->id_ano);
            if($plano === null){
                if($user->can('sispitDocente',['id' => $user->id]))
                    $situacaoplano = 'Você ainda não criou o PIT.';
            }
            else
                $situacaoplano = "$plano->situacao.";
            
            if($user->can('sispitGerenciarNucleo')){
                if(!$session->get('siscat_nucleo')){
                    $query = new Query;
                    $query->select('s.id_setor')
                    ->from('sisrh_pessoa p, sisrh_setor_pessoa sp, sisrh_setor s')
                    ->where("p.id_user = $user->id AND p.id_pessoa = sp.id_pessoa AND sp.id_setor = s.id_setor AND s.eh_nucleo_academico=1");
                    $session->set('siscat_nucleo',$query->one()['id_setor']);
                }
                
            }
            return $this->render('index', [
                'situacaoplano' => $situacaoplano,
                'plano' => $plano,
                'ano' => $ano,
            ]);
        }
        else
            $this->redirect(['sispitano/selecionarano']);
    }

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction','view' => '//site/error'],
        ];
    }

    public function actionAdicionarcomentario($id)
    {
        $session = Yii::$app->session;
        $user = Yii::$app->user;
        $ano = $session->get('siscat_ano');
        if($ano){
            $plano = SispitPlanoRelatorio::getPlanoRelatorio($user->id, $ano->id_ano);
        
            if($plano === null)
                throw new NotFoundHttpException('A página não foi encontrada');
            
            $model = SispitParecer::findOne($id);
            if($model->id_plano_relatorio != $plano->id_plano_relatorio)
                throw new ForbiddenHttpException('Você não tem permissão para acessar essa página');

            if ($model->load(Yii::$app->request->post()) && $model->save())
                return $this->redirect(['sispitplanorelatorio/submeter', 'id' => $plano->id_plano_relatorio]);
            
            return $this->redirect(['index']);
        }
        else
            return $this->redirect(['sispitano/selecionarano']);
    }
}
