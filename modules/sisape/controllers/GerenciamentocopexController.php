<?php

namespace app\modules\sisape\controllers;
use yii\web\Controller;
use yii\filters\AccessControl;
use Yii;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\modules\sisape\models\SisapeProjeto;
use app\modules\sisape\models\SisapeProjetoSearch;
use app\modules\sisape\models\SisapeParecer;
use app\modules\sisape\models\SisapeParecerSearch;
use app\modules\sisape\models\SisapeRelatorio;
use app\modules\sisape\models\SisapeRelatorioSearch;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisrh\models\SisrhSetor;
use app\modules\sisape\models\RelatorioForm;
use app\modules\sisape\models\SisapeProjetoIntegranteSearch;
use yii\helpers\Json;

class GerenciamentocopexController extends \yii\web\Controller
{
        /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => Yii::$app->user->can('sisapeAdministrar'),
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $projetos = new SisapeProjetoSearch();

        $dataProvider = $projetos->search(Yii::$app->request->queryParams);
        //Função retirada em 20/09/2021 por causa da remoção de tramitação pelo núcleo
        //Exibe os projetos que ainda necessitam da definição do núcleo
        //$projetos->situacao = 15;
        //$dataProviderDefinirNucleo = $projetos->search([]);
        
        return $this->render('index', [
            'searchModel' => $projetos,
            'dataProvider' => $dataProvider,
          //  'dataProviderDefinirNucleo' => $dataProviderDefinirNucleo
        ]);
    }

    public function actionAprovarprojeto($id)
    { 
        $projeto = SisapeProjeto::findOne($id);

        if($projeto->situacao <> 8 && $projeto->situacao <> 11 && $projeto->situacao <> 16)
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');

        $parecer = SisapeParecer::find()->where(['id_projeto' => $id,
            'tipo_parecerista' => SisapeParecer::PARECERISTA_COPEX,
            'atual' => 1,
        ])->one();

        if ($projeto->load(Yii::$app->request->post())) {
            switch($projeto->situacao){
                case 8:
                    if($projeto->data_aprovacao_copex)
                        $projeto->atualizarSituacao();
                break;
                case 11:
                    if(!$projeto->data_aprovacao_copex)
                        $projeto->situacao -= ($parecer ? 3 : 5);
                    $projeto->save();
                break;
                case 16:
                    if($projeto->data_aprovacao_copex){
                        $projeto->situacao = 11;
                        $projeto->save();
                    }
                    break;
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('aprovarprojeto', ['model' => $projeto, 'parecer' => $parecer]);
    }

    public function actionHomologarprojeto($id)
    { 
        $projeto = SisapeProjeto::findOne($id);

        if(($projeto->situacao <> 12) && ($projeto->situacao <> 11))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');

        if ($projeto->load(Yii::$app->request->post())) {
            switch($projeto->situacao){
                case 11:
                    if($projeto->data_homologacao_congregacao)
                        $projeto->atualizarSituacao();
                break;
                case 12:
                    if(!$projeto->data_homologacao_congregacao)
                        $projeto->situacao = 11;
                    $projeto->save();
                break;
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('homologarprojeto', ['model' => $projeto]);
    }

    public function actionDefinirparecerista($id)
    {
        $projeto = SisapeProjeto::findOne($id);
        
        if(($projeto->situacao < 6) || ($projeto->situacao > 10))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');
        
        $model = null;
        $id_pessoa = 0;
        if($projeto->situacao >= 7){//se o projeto já teve um parecerista emitido
            $model = SisapeParecer::find()->where(['id_projeto' => $id,
                'tipo_parecerista' => SisapeParecer::PARECERISTA_COPEX,
                'atual' => 1,
            ])->one();
            $id_pessoa = $model->id_pessoa;
        }
        else{//caso contrário, cria um novo parecer
            $model = new SisapeParecer;
            $model->id_projeto = $id;
            $model->tipo_parecerista = SisapeParecer::PARECERISTA_COPEX;
            $model->atual = 1;
        }
        
         if ($model->load(Yii::$app->request->post())) {
            //caso não tenha havido alteração do parecerista já definido anteriormente
            if($id_pessoa == $model->id_pessoa){
                $model->save();
                //redireciono para a tela inicial
                return $this->redirect(Yii::$app->request->referrer);
            }
            $model->data = date('Y-m-d');
            $transaction = $model::getDb()->beginTransaction();
            try{
                if($model->getIsNewRecord())
                        $projeto->atualizarSituacao(false, $model->id_pessoa);
                else{
                    $destinatario1 = array();
                    $destinatario2 = array();
                    $pessoa1 = SisrhPessoa::findOne($id_pessoa);
                    if($pessoa1->id_user)
                        $destinatario1[] = "{$pessoa1->user->username}@ufba.br";
                    if(count($pessoa1->emails) > 0)
                        $destinatario1[] = $pessoa1->emails[0];
                    if(count($destinatario1) > 0)
                        Yii::$app->mailer->compose('sisape_mensagem_alteracao_parecerista',['model' => $projeto]) // a view rendering result becomes the message body here
                            ->setFrom('copexims@ufba.br')
                            ->setTo($destinatario1)
                            ->setSubject('SISAPE')
                            ->send();
                    $pessoa2 = SisrhPessoa::findOne($model->id_pessoa);
                    if($pessoa2->id_user)
                        $destinatario2[] = "{$pessoa2->user->username}@ufba.br";
                    if(count($pessoa2->emails) > 0)
                        $destinatario2[] = $pessoa2->emails[0];
                    if(count($destinatario2) > 0)
                        Yii::$app->mailer->compose('sisape_mensagens_projeto',['projeto' => $projeto])
                            ->setFrom('copexims@ufba.br')
                            ->setTo($destinatario2)
                            ->setSubject('SISAPE')
                            ->send();
                    //Se o parecerista antigo já tiver emitido um parecer...
                    if($model->parecer != null){
                        //o parecer antigo vai deixar de ser o atual...
                        SisapeParecer::updateAll(['atual' => 0],"id_parecer = $model->id_parecer");
                        //e uma nova instância é criada id_parecer e parecer nulos.
                        $model->setIsNewRecord(true);
                        $model->id_parecer = null;
                        $model->parecer = null;
                    }
                }
                $model->save();
                $transaction->commit();

            } catch(\Throwable $t) {
                $transaction->rollBack();
                throw $t;
            }
            return $this->redirect(Yii::$app->request->referrer);
         }
        return $this->renderAjax('definirparecerista', ['model' => $model]);
    }

    public function actionParecer($id)
    {
        $searchModel = new SisapeParecerSearch();
        $params = ['id_projeto' => $id, 'tipo_parecerista' => SisapeParecer::PARECERISTA_COPEX,];
        $dataProvider = $searchModel->search($params);

        return $this->renderPartial('/sisapeparecer/impressao', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionResumo()
    {
        $query = new Query;

        $tipo_projeto = Yii::$app->request->post('tipo_projeto');
        
        $query->select('situacao, count(id_projeto) as total')
            ->from('sisape_projeto')
            ->groupBy('situacao')
            ->orderBy('situacao');
        
        if($tipo_projeto)
            $query->where(['tipo_projeto' => $tipo_projeto]);

        $dados = $query->all();

        return $this->render('resumo', ['dados' => $dados, 'tipo_projeto' => $tipo_projeto]);
    }

    public function actionDefinirnucleo()
    {
        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $projetoId = Yii::$app->request->post('editableKey');
            $model = SisapeProjeto::findOne($projetoId);

            $output = $message = '';

            // fetch the first entry in posted data (there should only be one entry 
            // anyway in this array for an editable submission)
            // - $posted is the posted data for Book without any indexes
            // - $post is the converted array for single model validation
            $posted = current($_POST['SisapeProjeto']);
            $post = ['SisapeProjeto' => $posted];

            if ($model->load($post)) {
                $model->situacao = 1;
                if($model->save())
                    $output = SisrhSetor::findOne($model->id_setor)->nome;
                else
                    $message = 'Ocorreu um erro ao gravar a informação.';
            }

            $out = Json::encode(['output'=>$output, 'message'=>$message]);
            // return ajax json encoded response and exit
            echo $out;
            return;
        }
    }

    public function actionProjetosimportados()
    {
        $projetos = new SisapeProjetoSearch();

        //Exibe somentes os projetos importados do sistema antigo e que ainda não foram aprovados pela copex
        $projetos->situacao = 16;
        $dataProvider = $projetos->search(Yii::$app->request->queryParams);
        
        return $this->render('projetosimportados', [
            'model' => $projetos,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRelatorios()
    {
        $relatorios = new SisapeRelatorioSearch();

        $dataProvider = $relatorios->search(Yii::$app->request->queryParams);
        
        return $this->render('relatorios', [
            'searchModel' => $relatorios,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAprovarrelatorio($id)
    { 
        $relatorio = SisapeRelatorio::findOne($id);

        if($relatorio->situacao <> 8 && $relatorio->situacao <> 11 && $relatorio->situacao <> 13)
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');

        $parecer = SisapeParecer::find()->where(['id_relatorio' => $id,
            'tipo_parecerista' => SisapeParecer::PARECERISTA_COPEX,
            'atual' => 1,
        ])->one();

        if ($relatorio->load(Yii::$app->request->post())) {
            switch($relatorio->situacao){
                case 8:
                    if($relatorio->data_aprovacao_copex)
                        $relatorio->atualizarSituacao();
                break;
                case 11:
                    if(!$relatorio->data_aprovacao_copex)
                        $relatorio->situacao -= ($parecer ? 3 : 5);
                    $relatorio->save();
                break;
                case 13:
                    if($relatorio->data_aprovacao_copex){
                        $relatorio->situacao = 11;
                        $relatorio->save();
                    }
                    break;
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('aprovarprojeto', ['model' => $relatorio, 'parecer' => $parecer]);
    }

    public function actionHomologarrelatorio($id)
    { 
        $relatorio = SisapeRelatorio::findOne($id);

        if(($relatorio->situacao <> 12) && ($relatorio->situacao <> 11))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');

        if ($relatorio->load(Yii::$app->request->post())) {
            switch($relatorio->situacao){
                case 11:
                    if($relatorio->data_homologacao_congregacao)
                        $relatorio->atualizarSituacao();
                break;
                case 12:
                    if(!$relatorio->data_homologacao_congregacao)
                        $relatorio->situacao = 11;
                    $relatorio->save();
                break;
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('homologarprojeto', ['model' => $relatorio]);
    }

    public function actionDefinirpareceristarelatorio($id)
    {
        $relatorio = SisapeRelatorio::findOne($id);
        
        if(($relatorio->situacao < 6) || ($relatorio->situacao > 10))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');
        
        $model = null;
        $id_pessoa = 0;
        if($relatorio->situacao >= 7){//se o relatorio já teve um parecerista emitido
            $model = SisapeParecer::find()->where(['id_relatorio' => $id,
                'tipo_parecerista' => SisapeParecer::PARECERISTA_COPEX,
                'atual' => 1,
            ])->one();
            $id_pessoa = $model->id_pessoa;
        }
        else{//caso contrário, cria um novo parecer
            $model = new SisapeParecer;
            $model->id_relatorio = $id;
            $model->tipo_parecerista = SisapeParecer::PARECERISTA_COPEX;
            $model->atual = 1;
        }
        
         if ($model->load(Yii::$app->request->post())) {
            //caso não tenha havido alteração do parecerista já definido anteriormente
            if($id_pessoa == $model->id_pessoa){
                $model->save();
                //redireciono para a tela inicial
                return $this->redirect(Yii::$app->request->referrer);
            }
            $model->data = date('Y-m-d');
            $transaction = $model::getDb()->beginTransaction();
            try{
                if($model->getIsNewRecord())
                        $relatorio->atualizarSituacao(false, $model->id_pessoa);
                else{
                    $destinatario1 = array();
                    $destinatario2 = array();
                    $pessoa1 = SisrhPessoa::findOne($id_pessoa);
                    if($pessoa1->id_user)
                        $destinatario1[] = "{$pessoa1->user->username}@ufba.br";
                    if(count($pessoa1->emails) > 0)
                        $destinatario1[] = $pessoa1->emails[0];
                    if(count($destinatario1) > 0)
                        Yii::$app->mailer->compose('sisape_mensagem_alteracao_parecerista',['model' => $relatorio]) // a view rendering result becomes the message body here
                            ->setFrom('copexims@ufba.br')
                            ->setTo($destinatario1)
                            ->setSubject('SISAPE')
                            ->send();
                    $pessoa2 = SisrhPessoa::findOne($model->id_pessoa);
                    if($pessoa2->id_user)
                        $destinatario2[] = "{$pessoa2->user->username}@ufba.br";
                    if(count($pessoa2->emails) > 0)
                        $destinatario2[] = $pessoa2->emails[0];
                    if(count($destinatario2) > 0)
                        Yii::$app->mailer->compose('sisape_mensagens_relatorio',['relatorio' => $relatorio])
                            ->setFrom('copexims@ufba.br')
                            ->setTo($destinatario2)
                            ->setSubject('SISAPE')
                            ->send();
                    //Se o parecerista antigo já tiver emitido um parecer...
                    if($model->parecer != null){
                        //o parecer antigo vai deixar de ser o atual...
                        SisapeParecer::updateAll(['atual' => 0],"id_parecer = $model->id_parecer");
                        //e uma nova instância é criada id_parecer e parecer nulos.
                        $model->setIsNewRecord(true);
                        $model->id_parecer = null;
                        $model->parecer = null;
                    }
                }
                $model->save();
                $transaction->commit();

            } catch(\Throwable $t) {
                $transaction->rollBack();
                throw $t;
            }
            return $this->redirect(Yii::$app->request->referrer);
         }
        return $this->renderAjax('definirparecerista', ['model' => $model]);
    }

    public function actionParecerrelatorio($id)
    {
        $searchModel = new SisapeParecerSearch();
        $params = ['id_relatorio' => $id, 'tipo_parecerista' => SisapeParecer::PARECERISTA_COPEX,];
        $dataProvider = $searchModel->search($params);

        return $this->renderPartial('/sisapeparecer/impressao', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRelatoriosimportados()
    {
        $relatorios = new SisapeRelatorioSearch();

        //Exibe somentes os relatorios importados do sistema antigo e que ainda não foram aprovados pela copex
        $relatorios->situacao = 13;
        $dataProvider = $relatorios->search(Yii::$app->request->queryParams);
        
        return $this->render('relatoriosimportados', [
            'model' => $relatorios,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRelatoriosistemaprojetos()
    {
        $model = new RelatorioForm(['scenario' => RelatorioForm::SCENARIO_PROJETOS]);
        $model->ano = date('Y');

        $dataProvider = $model->search(Yii::$app->request->queryParams);

        return $this->render('relatoriosistemaprojetos', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRelatoriosistemaparticipantes()
    {
        $model = new RelatorioForm(['scenario' => RelatorioForm::SCENARIO_PARTICIPANTES]);
        $model->ano = date('Y');

        $dataProvider = $model->search(Yii::$app->request->queryParams);
        
        return $this->render('relatoriosistemaparticipantes', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
}
