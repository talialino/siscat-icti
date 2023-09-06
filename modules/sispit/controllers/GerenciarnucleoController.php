<?php

namespace app\modules\sispit\controllers;
use yii\filters\AccessControl;
use Yii;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use app\modules\sispit\models\SispitPlanoRelatorio;
use app\modules\sispit\models\SispitParecer;
use app\modules\sispit\models\SispitParecerSearch;
use app\modules\sispit\helpers\Nucleo;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\modules\sisrh\models\SisrhPessoa;

class GerenciarnucleoController extends \yii\web\Controller
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
                        'allow' => Yii::$app->user->can('sispitGerenciarNucleo'),
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $user = Yii::$app->user;
        $setor = Yii::$app->session->get('siscat_nucleo');
        $ano = Yii::$app->session->get('siscat_ano');

        if(!$ano)
            return $this->redirect(['sispitano/selecionarano']);
        
        $query = new Query;
        
        $query->select('p.nome, pr.*')
            ->from('sisrh_pessoa p')
            ->where(['id_cargo' => 1])
            ->innerJoin('sisrh_setor_pessoa sp',"sp.id_pessoa = p.id_pessoa AND sp.id_setor = $setor")
            ->leftJoin('sispit_plano_relatorio pr',"p.id_user = pr.user_id AND pr.id_ano=$ano->id_ano")
            ->orderbY('p.nome ASC, pr.status ASC');
        $professores = $query->all();
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $professores,
            'pagination' => false,
            'sort' => false,
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionAprovarplanorelatorio($id)
    {
        $plano = SispitPlanoRelatorio::findOne($id);

        if(!Nucleo::validarNucleoPlanoRelatorio($plano->id_plano_relatorio,Yii::$app->session->get('siscat_nucleo')))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        if(($plano->status % 10 <> 3) && ($plano->status % 10 <> 5))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');

        if ($plano->load(Yii::$app->request->post())) {
            switch($plano->status){
                case 3:
                    if($plano->data_homologacao_nucleo_pit)
                        $plano->atualizarStatus();
                break;
                case 13:
                    if($plano->data_homologacao_nucleo_rit)
                        $plano->atualizarStatus();
                case 5:
                    if(!$plano->data_homologacao_nucleo_pit)
                        $plano->status -= 2;
                        $plano->save();
                break;
                case 15:
                    if(!$plano->data_homologacao_nucleo_rit)
                        $plano->status -= 2;
                        $plano->save();
                break;
            }
            return $this->redirect('index');
        }

        $parecer = SispitParecer::find()->where(['id_plano_relatorio' => $id,
                'tipo_parecerista' => SispitParecer::PARECERISTA_NUCLEO,
                'atual' => 1, 'pit_rit' => ($plano->isRitAvailable() ? 1 : 0)
            ])->one();
        return $this->renderAjax('aprovarplanorelatorio', ['model' => $plano, 'parecer' => $parecer]);
    }

    public function actionDefinirparecerista($id)
    {
        $plano = SispitPlanoRelatorio::findOne($id);
        
        if(($plano->status % 10 < 1) || ($plano->status % 10 > 4))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');
        
        
        if(!Nucleo::validarNucleoPlanoRelatorio($plano->id_plano_relatorio,Yii::$app->session->get('siscat_nucleo')))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        $model = null;
        $id_pessoa = 0;
        if($plano->status % 10 >= 2){//se o plano já teve um parecerista emitido
            $model = SispitParecer::find()->where(['id_plano_relatorio' => $id,
                'tipo_parecerista' => SispitParecer::PARECERISTA_NUCLEO,
                'atual' => 1, 'pit_rit' => ($plano->isRitAvailable() ? 1 : 0)
            ])->one();
            $id_pessoa = $model->id_pessoa;
        }
        else{//caso contrário, cria um novo parecer
            $model = new SispitParecer;
            $model->id_plano_relatorio = $id;
            $model->tipo_parecerista = SispitParecer::PARECERISTA_NUCLEO;
            $model->atual = 1;
            $model->pit_rit = ($plano->isRitAvailable() ? 1 : 0);
            $model->comissao_pit_rit = 0;
        }
        
         if ($model->load(Yii::$app->request->post())) {
            //caso não tenha havido alteração do parecerista já definido anteriormente
            if($id_pessoa == $model->id_pessoa)
                //redireciono para a tela inicial
                return $this->redirect('index');
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
        if(!Nucleo::validarNucleoPlanoRelatorio($id,Yii::$app->session->get('siscat_nucleo')))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');
        
        $searchModel = new SispitParecerSearch();
        $params = ['id_plano_relatorio' => $id, 'tipo_parecerista' => SispitParecer::PARECERISTA_NUCLEO,
            'pit_rit' => $pit_rit];
        $dataProvider = $searchModel->search($params);

        return $this->renderPartial('/sispitparecer/impressao', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pit_rit' => $pit_rit,
        ]);
    }

    public function actionView($id)
    {
        $model = SispitPlanoRelatorio::findOne($id);

        if($model === null)
            throw new NotFoundHttpException('A página requisitada não existe.');
            
        if(!Nucleo::validarNucleoPlanoRelatorio($id,Yii::$app->session->get('siscat_nucleo')))
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        return $this->render('/sispitplanorelatorio/_view.php', $model->planoRelatorioToArray());
    }

}
