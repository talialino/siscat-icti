<?php

namespace app\modules\sisai\controllers;

use app\modules\sisai\models\AtualizarTabelaAlunoForm;
use app\modules\sisai\models\ListaAtuvForm;
use app\modules\sisai\models\SisaiAluno;
use app\modules\sisai\models\SisaiPeriodoAvaliacao;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `sisai` module
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'avaliacao'],
                        'allow' => Yii::$app->user->can('sisai'),
                    ],
                    [
                        'actions' => ['listaatuv'],
                        'allow' => Yii::$app->user->can('sisaiAlunoGerenciar'),
                    ],
                    [
                        'actions' => ['atualizartabelaaluno'],
                        'allow' => Yii::$app->user->can('siscatAdministrar'),
                    ]
                ],
            ],
        ];
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        $periodoAvaliacao = $session->get('siscat_periodo_avaliacao', false);
        
        if($periodoAvaliacao === false)
        {
            $periodoAvaliacao = SisaiPeriodoAvaliacao::periodoAtivo();
            if($periodoAvaliacao)
            {
                $session->set('siscat_periodo_avaliacao', $periodoAvaliacao);
                $this->module->left = "{$this->module->id} - {$periodoAvaliacao->semestre->string}";
            }
        }

        return $this->render('index', ['periodoAvaliacao' => $periodoAvaliacao]);
    }

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction','view' => '//site/error'],
        ];
    }

    public function actionAvaliacao()
    {
        if(!Yii::$app->session->get('siscat_periodo_avaliacao', false))
            throw new ForbiddenHttpException("Não é possível acessar essa página: não existe período de avaliação vigente.");

        $contador = 0;
        if(Yii::$app->user->can('Discente'))
            $contador +=1;
        if(Yii::$app->user->can('Docente'))
            $contador +=2;
        if(Yii::$app->user->can('Tecnico'))
            $contador +=4;
        switch($contador)
        {
            case 1:
                return $this->redirect(['/sisai/sisaiavaliacao/discente']);
            case 2:
                return $this->redirect(['/sisai/sisaiavaliacao/docente']);
            case 4:
                return $this->redirect(['/sisai/sisaiavaliacao/tecnico']);
            default:
                return $this->render('avaliacao',['contador' => $contador]);
        }
    }

    public function actionAtualizartabelaaluno()
    {
        $model = new AtualizarTabelaAlunoForm();

        if($model->load(Yii::$app->request->post()))
        {
            $model->arquivo = $_FILES['AtualizarTabelaAlunoForm']['tmp_name']['arquivo'];
            if(SisaiAluno::atualizarTabelaSisaiAluno($model->arquivo))
                return 'Banco atualizado com sucesso. Ufa!';
            return 'Deu merda!';
            
        }
        return $this->render('atualizartabelaaluno', ['model' => $model]);
    }

    public function actionListaatuv()
    {
        $model = new ListaAtuvForm();

        if($model->load(Yii::$app->request->post()))
        {
           return $this->renderPartial('listaatuv', ['dataProvider' => $model->search()]);
        }

        return $this->render('listaatuvform', ['model' => $model]);
    }
}
