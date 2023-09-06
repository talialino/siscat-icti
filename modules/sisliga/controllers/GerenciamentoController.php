<?php

namespace app\modules\sisliga\controllers;
use yii\web\Controller;
use yii\filters\AccessControl;
use Yii;
use yii\db\Query;
use yii\web\ForbiddenHttpException;
use app\modules\sisliga\models\SisligaLigaAcademica;
use app\modules\sisliga\models\SisligaLigaAcademicaSearch;
use app\modules\sisliga\models\SisligaParecer;
use app\modules\sisliga\models\SisligaParecerSearch;
use app\modules\sisliga\models\SisligaRelatorio;
use app\modules\sisliga\models\SisligaRelatorioSearch;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisliga\models\RelatorioForm;
use yii\helpers\Json;

class GerenciamentoController extends Controller
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
                        'allow' => Yii::$app->user->can('sisligaAdministrar'),
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $ligas = new SisligaLigaAcademicaSearch();

        $dataProvider = $ligas->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $ligas,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAprovarligaacademica($id)
    { 
        $liga = SisligaLigaAcademica::findOne($id);

        if($liga->situacao <> 3 && $liga->situacao <> 6)
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');

        $parecer = SisligaParecer::find()->where(['id_liga_academica' => $id,
            'atual' => 1,
        ])->one();

        if ($liga->load(Yii::$app->request->post())) {
            switch($liga->situacao){
                case 3:
                    if($liga->data_aprovacao_comissao)
                        $liga->atualizarSituacao();
                break;
                case 6:
                    if(!$liga->data_aprovacao_comissao)
                        $liga->situacao -= ($parecer ? 3 : 5);
                    $liga->save();
                break;
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('aprovarligaacademica', ['model' => $liga, 'parecer' => $parecer]);
    }

    public function actionHomologarligaacademica($id)
    { 
        $liga = SisligaLigaAcademica::findOne($id);

        if(($liga->situacao <> 7) && ($liga->situacao <> 6))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');

        if ($liga->load(Yii::$app->request->post())) {
            switch($liga->situacao){
                case 6:
                    if($liga->data_homologacao_congregacao)
                        $liga->atualizarSituacao();
                break;
                case 7:
                    if(!$liga->data_homologacao_congregacao)
                        $liga->situacao = 6;
                    $liga->save(false);
                break;
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('homologarligaacademica', ['model' => $liga]);
    }

    public function actionDefinirparecerista($id)
    {
        $liga = SisligaLigaAcademica::findOne($id);
        
        if(($liga->situacao < 1) || ($liga->situacao > 5))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');
        
        $model = null;
        $id_pessoa = 0;
        if($liga->situacao >= 2){//se a liga já teve um parecerista emitido
            $model = SisligaParecer::find()->where(['id_liga_academica' => $id,
                'atual' => 1,
            ])->one();
            $id_pessoa = $model->id_pessoa;
        }
        else{//caso contrário, cria um novo parecer
            $model = new SisligaParecer;
            $model->id_liga_academica = $id;
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
                        $liga->atualizarSituacao(false, $model->id_pessoa);
                else{
                    $destinatario1 = array();
                    $destinatario2 = array();
                    $pessoa1 = SisrhPessoa::findOne($id_pessoa);
                    if($pessoa1->id_user)
                        $destinatario1[] = "{$pessoa1->user->username}@ufba.br";
                    if(count($pessoa1->emails) > 0)
                        $destinatario1[] = $pessoa1->emails[0];
                    if(count($destinatario1) > 0)
                        Yii::$app->mailer->compose('sisliga_mensagem_alteracao_parecerista',['model' => $liga]) // a view rendering result becomes the message body here
                            ->setFrom('copexims@ufba.br')
                            ->setTo($destinatario1)
                            ->setSubject('SISliga')
                            ->send();
                    $pessoa2 = SisrhPessoa::findOne($model->id_pessoa);
                    if($pessoa2->id_user)
                        $destinatario2[] = "{$pessoa2->user->username}@ufba.br";
                    if(count($pessoa2->emails) > 0)
                        $destinatario2[] = $pessoa2->emails[0];
                    if(count($destinatario2) > 0)
                        Yii::$app->mailer->compose('sisliga_mensagens_liga_relatorio',['documento' => $liga])
                            ->setFrom('copexims@ufba.br')
                            ->setTo($destinatario2)
                            ->setSubject('SISliga')
                            ->send();
                    //Se o parecerista antigo já tiver emitido um parecer...
                    if($model->parecer != null){
                        //o parecer antigo vai deixar de ser o atual...
                        SisligaParecer::updateAll(['atual' => 0],"id_parecer = $model->id_parecer");
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
        $searchModel = new SisligaParecerSearch();
        $params = ['id_liga_academica' => $id,];
        $dataProvider = $searchModel->search($params);

        return $this->renderPartial('/sisligaparecer/impressao', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionResumo()
    {
        $query = new Query;
        
        $query->select('situacao, count(id_liga_academica) as total')
            ->from('sisliga_liga_academica')
            ->groupBy('situacao')
            ->orderBy('situacao');

        $dados = $query->all();

        return $this->render('resumo', ['dados' => $dados]);
    }

    public function actionRelatorios()
    {
        $relatorios = new SisligaRelatorioSearch();

        $dataProvider = $relatorios->search(Yii::$app->request->queryParams);
        
        return $this->render('relatorios', [
            'searchModel' => $relatorios,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAprovarrelatorio($id)
    { 
        $relatorio = SisligaRelatorio::findOne($id);

        if($relatorio->situacao <> 3 && $relatorio->situacao <> 6)
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');

        $parecer = SisligaParecer::find()->where(['id_relatorio' => $id,
            'atual' => 1,
        ])->one();

        if ($relatorio->load(Yii::$app->request->post())) {
            switch($relatorio->situacao){
                case 3:
                    if($relatorio->data_aprovacao_comissao)
                        $relatorio->atualizarSituacao();
                break;
                case 6:
                    if(!$relatorio->data_aprovacao_comissao)
                        $relatorio->situacao -= ($parecer ? 3 : 5);
                    $relatorio->save();
                break;
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('aprovarligaacademica', ['model' => $relatorio, 'parecer' => $parecer]);
    }

    public function actionHomologarrelatorio($id)
    { 
        $relatorio = SisligaRelatorio::findOne($id);

        if(($relatorio->situacao <> 7) && ($relatorio->situacao <> 6))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');

        if ($relatorio->load(Yii::$app->request->post())) {
            switch($relatorio->situacao){
                case 6:
                    if($relatorio->data_homologacao_congregacao)
                        $relatorio->atualizarSituacao();
                break;
                case 7:
                    if(!$relatorio->data_homologacao_congregacao)
                        $relatorio->situacao = 6;
                    $relatorio->save();
                break;
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('homologarligaacademica', ['model' => $relatorio]);
    }

    public function actionDefinirpareceristarelatorio($id)
    {
        $relatorio = SisligaRelatorio::findOne($id);
        
        if(($relatorio->situacao < 1) || ($relatorio->situacao > 5))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');
        
        $model = null;
        $id_pessoa = 0;
        if($relatorio->situacao >= 2){//se o relatorio já teve um parecerista emitido
            $model = SisligaParecer::find()->where(['id_relatorio' => $id,
                'atual' => 1,
            ])->one();
            $id_pessoa = $model->id_pessoa;
        }
        else{//caso contrário, cria um novo parecer
            $model = new SisligaParecer;
            $model->id_relatorio = $id;
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
                        Yii::$app->mailer->compose('sisliga_mensagem_alteracao_parecerista',['model' => $relatorio]) // a view rendering result becomes the message body here
                            ->setFrom('copexims@ufba.br')
                            ->setTo($destinatario1)
                            ->setSubject('SISliga')
                            ->send();
                    $pessoa2 = SisrhPessoa::findOne($model->id_pessoa);
                    if($pessoa2->id_user)
                        $destinatario2[] = "{$pessoa2->user->username}@ufba.br";
                    if(count($pessoa2->emails) > 0)
                        $destinatario2[] = $pessoa2->emails[0];
                    if(count($destinatario2) > 0)
                        Yii::$app->mailer->compose('sisliga_mensagens_liga_relatorio',['documento' => $relatorio])
                            ->setFrom('copexims@ufba.br')
                            ->setTo($destinatario2)
                            ->setSubject('SISliga')
                            ->send();
                    //Se o parecerista antigo já tiver emitido um parecer...
                    if($model->parecer != null){
                        //o parecer antigo vai deixar de ser o atual...
                        SisligaParecer::updateAll(['atual' => 0],"id_parecer = $model->id_parecer");
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
        $searchModel = new SisligaParecerSearch();
        $params = ['id_relatorio' => $id,];
        $dataProvider = $searchModel->search($params);

        return $this->renderPartial('/sisligaparecer/impressao', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRelatoriosistemaligas()
    {
        $model = new RelatorioForm(['scenario' => RelatorioForm::SCENARIO_LIGAS]);
        $model->ano = date('Y');

        $dataProvider = $model->search(Yii::$app->request->queryParams);

        return $this->render('relatoriosistemaligas', [
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
