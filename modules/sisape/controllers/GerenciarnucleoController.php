<?php

namespace app\modules\sisape\controllers;
use yii\web\Controller;
use yii\filters\AccessControl;
use Yii;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\modules\sisape\models\SisapeProjeto;
use app\modules\sisape\models\SisapeProjetoSearch;
use app\modules\sisape\models\SisapeRelatorio;
use app\modules\sisape\models\SisapeRelatorioSearch;
use app\modules\sisape\models\SisapeParecer;
use app\modules\sisape\models\SisapeParecerSearch;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisrh\models\SisrhSetor;

class GerenciarnucleoController extends \yii\web\Controller
{
        /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => Yii::$app->user->can('sisapeGerenciarNucleo'),
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $pessoa = Yii::$app->session->get('siscat_pessoa');
                
        $nucleo = $pessoa->getSisrhSetores()->where(['eh_nucleo_academico' => 1])->one();
        
        $projetos = new SisapeProjetoSearch();

        $projetos->id_setor = $nucleo->id_setor;

        $dataProvider = $projetos->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $projetos,
            'dataProvider' => $dataProvider]);
    }

    public function actionAprovarprojeto($id)
    {
        $projeto = SisapeProjeto::findOne($id);

        $pessoa = Yii::$app->session->get('siscat_pessoa');

        if(!$pessoa->getSisrhSetorPessoas()->where(['id_setor' => $projeto->id_setor])->exists())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        if(($projeto->situacao <> 3) && ($projeto->situacao <> 6))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');

        $parecer = SisapeParecer::find()->where(['id_projeto' => $id,
            'tipo_parecerista' => SisapeParecer::PARECERISTA_NUCLEO,
            'atual' => 1,
        ])->one();

        if ($projeto->load(Yii::$app->request->post())) {
            switch($projeto->situacao){
                case 3:
                    if($projeto->data_aprovacao_nucleo)
                        $projeto->atualizarSituacao();
                break;
                case 6:
                    if(!$projeto->data_aprovacao_nucleo)
                        $projeto->situacao -= ($parecer ? 3 : 5);
                    $projeto->save();
                break;
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('aprovarprojeto', ['model' => $projeto, 'parecer' => $parecer]);
    }

    public function actionDefinirparecerista($id)
    {
        $projeto = SisapeProjeto::findOne($id);
        
        if(($projeto->situacao < 1) || ($projeto->situacao > 5))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');
        
        $pessoa = Yii::$app->session->get('siscat_pessoa');

        if(!$pessoa->getSisrhSetorPessoas()->where(['id_setor' => $projeto->id_setor])->exists())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        $model = null;
        $id_pessoa = 0;
        if($projeto->situacao >= 2){//se o projeto já teve um parecerista emitido
            $model = SisapeParecer::find()->where(['id_projeto' => $id,
                'tipo_parecerista' => SisapeParecer::PARECERISTA_NUCLEO,
                'atual' => 1,
            ])->one();
            $id_pessoa = $model->id_pessoa;
        }
        else{//caso contrário, cria um novo parecer
            $model = new SisapeParecer;
            $model->id_projeto = $id;
            $model->tipo_parecerista = SisapeParecer::PARECERISTA_NUCLEO;
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

            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $t) {
                $transaction->rollBack();
                throw $t;
            }
            return $this->redirect(Yii::$app->request->referrer);
         }
        return $this->renderAjax('definirparecerista', ['model' => $model, 'projeto' => $projeto]);
    }

    public function actionParecer($id)
    {
        $projeto = SisapeProjeto::findOne($id);

        $pessoa = Yii::$app->session->get('siscat_pessoa');

        if(!$pessoa->getSisrhSetorPessoas()->where(['id_setor' => $projeto->id_setor])->andWhere(['<','funcao',2])->exists())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');
        
        $searchModel = new SisapeParecerSearch();
        $params = ['id_projeto' => $id, 'tipo_parecerista' => SisapeParecer::PARECERISTA_NUCLEO,
        ];
        $dataProvider = $searchModel->search($params);

        return $this->renderPartial('/sisapeparecer/impressao', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRelatorios()
    {
        $pessoa = Yii::$app->session->get('siscat_pessoa');
                
        $nucleo = $pessoa->getSisrhSetores()->where(['eh_nucleo_academico' => 1])->one();
        
        $relatorios = new SisapeRelatorioSearch();

        $relatorios->id_setor = $nucleo->id_setor;

        $dataProvider = $relatorios->search(Yii::$app->request->queryParams);
        
        return $this->render('relatorios', [
            'searchModel' => $relatorios,
            'dataProvider' => $dataProvider]);
    }

    public function actionAprovarrelatorio($id)
    {
        $relatorio = SisapeRelatorio::findOne($id);

        $pessoa = Yii::$app->session->get('siscat_pessoa');

        if(!$pessoa->getSisrhSetorPessoas()->where(['id_setor' => $relatorio->projeto->id_setor])->exists())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        if(($relatorio->situacao <> 3) && ($relatorio->situacao <> 6))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');

        $parecer = SisapeParecer::find()->where(['id_relatorio' => $id,
            'tipo_parecerista' => SisapeParecer::PARECERISTA_NUCLEO,
            'atual' => 1,
        ])->one();

        if ($relatorio->load(Yii::$app->request->post())) {
            switch($relatorio->situacao){
                case 3:
                    if($relatorio->data_aprovacao_nucleo)
                        $relatorio->atualizarSituacao();
                break;
                case 6:
                    if(!$relatorio->data_aprovacao_nucleo)
                        $relatorio->situacao -= ($parecer ? 3 : 5);
                    $relatorio->save();
                break;
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('aprovarprojeto', ['model' => $relatorio, 'parecer' => $parecer]);
    }

    public function actionDefinirpareceristarelatorio($id)
    {
        $relatorio = SisapeRelatorio::findOne($id);
        
        if(($relatorio->situacao < 1) || ($relatorio->situacao > 5))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');
        
        $pessoa = Yii::$app->session->get('siscat_pessoa');

        if(!$pessoa->getSisrhSetorPessoas()->where(['id_setor' => $relatorio->projeto->id_setor])->exists())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        $model = null;
        $id_pessoa = 0;
        if($relatorio->situacao >= 2){//se o relatorio já teve um parecerista emitido
            $model = SisapeParecer::find()->where(['id_relatorio' => $id,
                'tipo_parecerista' => SisapeParecer::PARECERISTA_NUCLEO,
                'atual' => 1,
            ])->one();
            $id_pessoa = $model->id_pessoa;
        }
        else{//caso contrário, cria um novo parecer
            $model = new SisapeParecer;
            $model->id_relatorio = $id;
            $model->tipo_parecerista = SisapeParecer::PARECERISTA_NUCLEO;
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

            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $t) {
                $transaction->rollBack();
                throw $t;
            }
            return $this->redirect(Yii::$app->request->referrer);
         }
        return $this->renderAjax('definirparecerista', ['model' => $model, 'projeto' => $relatorio->projeto]);
    }

    public function actionParecerrelatorio($id)
    {
        $relatorio = SisapeRelatorio::findOne($id);

        $pessoa = Yii::$app->session->get('siscat_pessoa');

        if(!$pessoa->getSisrhSetorPessoas()->where(['id_setor' => $relatorio->projeto->id_setor])->andWhere(['<','funcao',2])->exists())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');
        
        $searchModel = new SisapeParecerSearch();
        $params = ['id_relatorio' => $id, 'tipo_parecerista' => SisapeParecer::PARECERISTA_NUCLEO,
        ];
        $dataProvider = $searchModel->search($params);

        return $this->renderPartial('/sisapeparecer/impressao', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}