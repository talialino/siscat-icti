<?php

namespace app\modules\sisai\controllers;

use app\modules\sisai\helpers\GeradorRelatorio;
use app\modules\sisai\models\RelatorioForm;
use app\modules\sisai\helpers\RelatorioSistemasAntigos;
use Yii;
use yii\filters\AccessControl;

class RelatoriosController extends \yii\web\Controller
{
    public function behaviors()
    {
        $sisaiAdmin = Yii::$app->user->can('sisaiAdministrar');
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index','ajaxcomponentescurriculares'],
                        'allow' => Yii::$app->user->can('sisai'),
                    ],
                    [
                        'actions' => ['avaliacaodocente'],
                        'allow' => $sisaiAdmin || Yii::$app->user->can('Docente'),
                    ],
                    [
                        'actions' => ['autoavaliacaodiscente','autoavaliacaodocente','avaliacaodiscente','avaliacaoambientevirtual',
                            'avaliacaoinfraestrutura','avaliacaoacessibilidade','avaliacaogestaoacademica',
                            'avaliacaoqualidadevida','avaliacaocondicoestrabalho','avaliacaotecnicos'],
                        'allow' => $sisaiAdmin,
                    ],
                ],
            ],
        ];
    }

    public function actionAvaliacaodocente()
    {
        $model = new RelatorioForm(['scenario' => RelatorioForm::SCENARIO_DOCENTE]);
        
        if(Yii::$app->user->can('Docente'))
            $model->id_pessoa = Yii::$app->session->get('siscat_pessoa')->id_pessoa;

        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            
            $relatorioQuestionario = $model->id_semestre > 22 ? GeradorRelatorio::relatorioAvaliacaoDocente($model) :
                RelatorioSistemasAntigos::relatorioAvaliacaoDocente($model);

            return $this->render('avaliacaodocente', ['model' => $model,
                'relatorio' => $relatorioQuestionario[0], 'tipoQuestionario' => $relatorioQuestionario[1]]);
        }
        return $this->render('avaliacaodocente', ['model' => $model, 'relatorio' => false]);
    }

    public function actionAjaxcomponentescurriculares($id_pessoa = false, $id_semestre)
    {
        $model = new RelatorioForm();
        $model->id_pessoa = $id_pessoa;
        $model->id_semestre = $id_semestre;
        $lista = $model->listaComponentesColegiados();
        
        $saida = count($lista) > 0 ? '<option value>Selecione o componente curricular</option>' : 
            '<option value>Não foi encontrado nenhum componente com essas informações</option>';

        foreach($lista as $k => $v)
            $saida .= "<option value='$k'>$v</option>";
        
        return $saida;
    }
    
    public function actionAvaliacaodiscente()
    {
        $model = new RelatorioForm(['scenario' => RelatorioForm::SCENARIO_DISCENTE]);
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $relatorioQuestionario = $model->id_semestre > 22 ? GeradorRelatorio::relatorioAvaliacaoDiscente($model) :
                RelatorioSistemasAntigos::relatorioAvaliacaoDiscente($model);
                
            return $this->render('avaliacaodiscente', ['model' => $model, 'relatorio' => $relatorioQuestionario]);
        }

        return $this->render('avaliacaodiscente', ['model' => $model, 'relatorio' => false]);
    }
    
    public function actionAutoavaliacaodiscente()
    {
        $model = new RelatorioForm(['scenario' => RelatorioForm::SCENARIO_DISCENTE]);
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $relatorioQuestionario = $model->id_semestre > 22 ? GeradorRelatorio::relatorioAutoavaliacaoDiscente($model) :
                RelatorioSistemasAntigos::relatorioAvaliacaoDiscente($model);
                
            return $this->render('autoavaliacaodiscente', ['model' => $model, 'relatorio' => $relatorioQuestionario]);
        }

        return $this->render('autoavaliacaodiscente', ['model' => $model, 'relatorio' => false]);
    }
    
    public function actionAutoavaliacaodocente()
    {
        $model = new RelatorioForm(['scenario' => RelatorioForm::SCENARIO_DISCENTE]);
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            
            $relatorioQuestionario = $model->id_semestre > 22 ? GeradorRelatorio::relatorioAutoavaliacaoDocente($model) : false;
                
            return $this->render('autoavaliacaodocente', ['model' => $model, 'relatorio' => $relatorioQuestionario]);
        }

        return $this->render('autoavaliacaodocente', ['model' => $model, 'relatorio' => false]);
    }
    
    public function actionAvaliacaoambientevirtual()
    {
        $model = new RelatorioForm();
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $relatorioQuestionario = GeradorRelatorio::relatorioAvaliacao($model);
                
            return $this->render('avaliacaoambientevirtual', ['model' => $model, 'relatorio' => $relatorioQuestionario]);
        }

        return $this->render('avaliacaoambientevirtual', ['model' => $model, 'relatorio' => false]);
    }
    
    public function actionAvaliacaoinfraestrutura()
    {
        $model = new RelatorioForm();
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            
            $relatorioQuestionario = $model->id_semestre > 22 ? GeradorRelatorio::relatorioAvaliacao($model) :
                RelatorioSistemasAntigos::relatorioAvaliacaoDocente($model);
                
            return $this->render('avaliacaoinfraestrutura', ['model' => $model, 'relatorio' => $relatorioQuestionario]);
        }

        return $this->render('avaliacaoinfraestrutura', ['model' => $model, 'relatorio' => false]);
    }
    
    public function actionAvaliacaoacessibilidade()
    {
        $model = new RelatorioForm();
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $relatorioQuestionario = GeradorRelatorio::relatorioAvaliacao($model);
                
            return $this->render('avaliacaoacessibilidade', ['model' => $model, 'relatorio' => $relatorioQuestionario]);
        }

        return $this->render('avaliacaoacessibilidade', ['model' => $model, 'relatorio' => false]);
    }
    
    public function actionAvaliacaogestaoacademica()
    {
        $model = new RelatorioForm();
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $relatorioQuestionario = GeradorRelatorio::relatorioAvaliacao($model);
                
            return $this->render('avaliacaogestaoacademica', ['model' => $model, 'relatorio' => $relatorioQuestionario]);
        }

        return $this->render('avaliacaogestaoacademica', ['model' => $model, 'relatorio' => false]);
    }
    
    public function actionAvaliacaoqualidadevida()
    {
        $model = new RelatorioForm();
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $relatorioQuestionario = GeradorRelatorio::relatorioAvaliacao($model);
                
            return $this->render('avaliacaoqualidadevida', ['model' => $model, 'relatorio' => $relatorioQuestionario]);
        }

        return $this->render('avaliacaoqualidadevida', ['model' => $model, 'relatorio' => false]);
    }
    
    public function actionAvaliacaocondicoestrabalho()
    {
        $model = new RelatorioForm();
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $relatorioQuestionario = GeradorRelatorio::relatorioAvaliacao($model);
                
            return $this->render('avaliacaocondicoestrabalho', ['model' => $model, 'relatorio' => $relatorioQuestionario]);
        }

        return $this->render('avaliacaocondicoestrabalho', ['model' => $model, 'relatorio' => false]);
    }
    
    public function actionAvaliacaotecnicos()
    {
        $model = new RelatorioForm();
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $relatorioQuestionario = GeradorRelatorio::relatorioAvaliacao($model);
                
            return $this->render('avaliacaotecnicos', ['model' => $model, 'relatorio' => $relatorioQuestionario]);
        }

        return $this->render('avaliacaotecnicos', ['model' => $model, 'relatorio' => false]);
    }
}
