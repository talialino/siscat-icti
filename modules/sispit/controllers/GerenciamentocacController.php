<?php

namespace app\modules\sispit\controllers;
use yii\web\Controller;
use yii\filters\AccessControl;
use Yii;
use yii\db\Query;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use app\modules\sispit\models\SispitPlanoRelatorio;
use app\modules\sispit\models\SispitParecer;
use app\modules\sispit\models\SispitParecerSearch;
use app\modules\sisrh\models\SisrhPessoa;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

class GerenciamentocacController extends Controller
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
                        'allow' => Yii::$app->user->can('sispitAdministrar'),
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $ano = Yii::$app->session->get('siscat_ano');

        if(!$ano)
            return $this->redirect(['sispitano/selecionarano']);
        
        $query = SisrhPessoa::find()->select(new Expression('sisrh_pessoa.*,'.$ano->id_ano.' as sispitAno'))
        ->where(['id_cargo' => 1]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'nome' => SORT_ASC,
                ]
            ],
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionAprovarplanorelatorio($id)
    {
        $plano = SispitPlanoRelatorio::findOne($id);

        if(($plano->status % 10 <> 7) && ($plano->status % 10 <> 9))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');

        if ($plano->load(Yii::$app->request->post())) {
            switch($plano->status){
                case 7:
                    if($plano->data_homologacao_cac_pit)
                        $plano->atualizarStatus();
                break;
                case 17:
                    if($plano->data_homologacao_cac_rit)
                        $plano->atualizarStatus();
                case 9:
                    if(!$plano->data_homologacao_cac_pit)
                        $plano->status -= 2;
                        $plano->save();
                break;
                case 19:
                    if(!$plano->data_homologacao_cac_rit)
                        $plano->status -= 2;
                        $plano->save();
                break;
            }
            return $this->redirect('index');
        }

        $parecer = SispitParecer::find()->where(['id_plano_relatorio' => $id,
                'tipo_parecerista' => SispitParecer::PARECERISTA_CAC,
                'atual' => 1, 'pit_rit' => ($plano->isRitAvailable() ? 1 : 0)
            ])->one();
        return $this->renderAjax('aprovarplanorelatorio', ['model' => $plano, 'parecer' => $parecer]);
    }

    public function actionDefinirparecerista($id)
    {
        $plano = SispitPlanoRelatorio::findOne($id);
        
        if(($plano->status % 10 < 5) || ($plano->status % 10 > 8))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');
        
        $model = null;
        $id_pessoa = 0;
        if($plano->status % 10 >= 6){//se o plano já teve um parecerista emitido
            $model = SispitParecer::find()->where(['id_plano_relatorio' => $id,
                'tipo_parecerista' => SispitParecer::PARECERISTA_CAC,
                'atual' => 1, 'pit_rit' => ($plano->isRitAvailable() ? 1 : 0)
            ])->one();
            $id_pessoa = $model->id_pessoa;
        }
        else{//caso contrário, cria um novo parecer
            $model = new SispitParecer;
            $model->id_plano_relatorio = $id;
            $model->tipo_parecerista = SispitParecer::PARECERISTA_CAC;
            $model->atual = 1;
            $model->pit_rit = ($plano->isRitAvailable() ? 1 : 0);
        }
        
         if ($model->load(Yii::$app->request->post())) {
            //caso não tenha havido alteração do parecerista já definido anteriormente
            if($id_pessoa == $model->id_pessoa)
                //redireciono para a tela inicial
                return $this->redirect('index');
            
            //As linhas a seguir verificam se a pessoa faz parte da comissao de pit e rit. Se fizer, o valor de comissao_pit_rit
            //de sispitparecer será 1, caso contrário, será 0
            $model->comissao_pit_rit = 0;
            foreach($model->pessoa->sisrhComissoes as $comissao)
                if($comissao->eh_comissao_pit_rit)
                    $model->comissao_pit_rit = 1;
            
            $model->data = date('Y-m-d');
            $transaction = $model::getDb()->beginTransaction();
            try{
                if($model->getIsNewRecord())
                        $plano->atualizarStatus(false, $model->id_pessoa);
                else{//Caso altere o parecer, envia e-mail para o antigo informando que não é mais o parecerista
                    //e um outro e-mail para informar o novo parecerista
                    $destinatario1 = array();
                    $destinatario2 = array();
                    $pessoa1 = SisrhPessoa::findOne($id_pessoa);
                    if($pessoa1->id_user)
                        $destinatario1[] = "{$pessoa1->user->username}@ufba.br";
                    if(count($pessoa1->emails) > 0)
                        $destinatario1[] = $pessoa1->emails[0];
                    if(count($destinatario1) > 0)
                        Yii::$app->mailer->compose('sispit_mensagem_alteracao_parecerista',['plano' => $plano]) // a view rendering result becomes the message body here
                            ->setFrom('coordenacaoacademica@ufba.br')
                            ->setTo($destinatario1)
                            ->setSubject('SISPIT')
                            ->send();
                    $pessoa2 = SisrhPessoa::findOne($model->id_pessoa);
                    if($pessoa2->id_user)
                        $destinatario2[] = "{$pessoa2->user->username}@ufba.br";
                    if(count($pessoa2->emails) > 0)
                        $destinatario2[] = $pessoa2->emails[0];
                    if(count($destinatario2) > 0)
                        Yii::$app->mailer->compose('sispit_mensagens_plano_relatorio',['plano' => $plano])
                            ->setFrom('coordenacaoacademica@ufba.br')
                            ->setTo($destinatario2)
                            ->setSubject('SISPIT')
                            ->send();
                    //Se o parecerista antigo já tiver emitido um parecer...
                    if($model->parecer != null){
                        //o parecer antigo vai deixar de ser o atual...
                        SispitParecer::updateAll(['atual' => 0],"id_parecer = $model->id_parecer");
                        //e uma nova instância é criada id_parecer e parecer nulos.
                        $model->setIsNewRecord(true);
                        $model->id_parecer = null;
                        $model->parecer = null;
                        $model->comentario = null;
                    }
                }
                $model->save();
                $transaction->commit();

            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $t) {
                $transaction->rollBack();
                throw $t;
            }
            return $this->redirect('index');
         }
        return $this->renderAjax('definirparecerista', ['model' => $model]);
    }

    public function actionParecer($id,$pit_rit)
    {
        $searchModel = new SispitParecerSearch();
        $params = ['id_plano_relatorio' => $id, 'tipo_parecerista' => SispitParecer::PARECERISTA_CAC,
            'pit_rit' => $pit_rit];
        $dataProvider = $searchModel->search($params);

        return $this->renderPartial('/sispitparecer/impressao', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pit_rit' => $pit_rit,
        ]);
    }

    public function actionResumo()
    {
        $ano = Yii::$app->session->get('siscat_ano');
        if(!$ano)
            return $this->redirect(['sispitano/selecionarano']);
        
        $query = new Query;
        $dados = array();
        $query->select('pr.status')
        ->from('sisrh_pessoa p')
        ->where(['id_cargo' => 1])
        ->leftJoin('sispit_plano_relatorio pr',"p.id_user = pr.user_id AND pr.id_ano = $ano->id_ano");
        $professores = $query->all();
        foreach($professores as $professor){
            if($professor['status'] == null)
                $professor['status'] = 0;
            $dados[$professor['status']] = (isset($dados[$professor['status']]) ? $dados[$professor['status']] : 0) + 1;
        }

        return $this->render('resumo', ['dados' => $dados]);
    }

    public function actionView($id)
    {
        $model = SispitPlanoRelatorio::findOne($id);

        if($model === null)
            throw new NotFoundHttpException('A página requisitada não existe.');

        return $this->render('view.php', $model->planoRelatorioToArray());
    }

    public function actionDefinirsituacao($id)
    {
        $model = SispitPlanoRelatorio::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->status < 5)
                $model->status = 5;
            else if($model->status > 10 && $model->status < 15)
                $model->status = 15;
            $model->save();
            $this->redirect(['index']);
        }

        return $this->renderAjax('definirsituacao', ['model' => $model]);
    }

        /**
     * Docente que teve rit parcial aprovado, pode editar novamente o retornando
     * o status do modelo para o valor 10 (RIT não preenchido ou não submetido)
     */
    public function actionPreencherritfinal($id)
    {
        $model = SispitPlanoRelatorio::findOne($id);

        if(!($model->status == 19 && $model->situacao_estagio_probatorio == 1))
            throw new ForbiddenHttpException('Você não pode realizar esta operação.');
            
        $model->status = 10;
        $model->situacao_estagio_probatorio = 0;
        $model->save();
        
        return $this->redirect('index');
    }
}
