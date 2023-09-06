<?php

namespace app\modules\sispit\controllers;
use yii\filters\AccessControl;
use Yii;
use app\modules\sispit\models\SispitPlanoRelatorio;
use app\modules\sispit\models\SispitPlanoRelatorioSearch;
use app\modules\sispit\models\SispitParecer;
use app\modules\sisrh\models\SisrhPessoa;
use yii\web\ForbiddenHttpException;

class GerenciarcomissaoController extends \yii\web\Controller
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
                        'allow' => Yii::$app->user->can('ComissaoPitRit'),
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new SispitPlanoRelatorioSearch();
        $dataProvider = $searchModel->searchAvaliacaoCac(Yii::$app->request->queryParams); 
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
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
             $model->comissao_pit_rit = 1;
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
}
