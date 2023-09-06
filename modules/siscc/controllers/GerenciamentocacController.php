<?php

namespace app\modules\siscc\controllers;
use yii\web\Controller;
use yii\filters\AccessControl;
use Yii;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\modules\siscc\models\SisccProgramaComponenteCurricular;
use app\modules\siscc\models\SisccProgramaComponenteCurricularSearch;
use app\modules\siscc\models\SisccSemestre;
use app\modules\siscc\models\SisccParecer;
use app\modules\siscc\models\SisccParecerSearch;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisrh\models\SisrhSetor;

class GerenciamentocacController extends \yii\web\Controller
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
                        'allow' => Yii::$app->user->can('sisccAdministrar'),
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($sort = false)
    {
        $programas = new SisccProgramaComponenteCurricularSearch();
        $programas->id_semestre = SisccSemestre::find()->max('id_semestre');

        $dataProvider = $programas->search(Yii::$app->request->queryParams,$sort);
        
        return $this->render('index', [
            'searchModel' => $programas,
            'dataProvider' => $dataProvider]);
    }

    public function actionAprovarprograma($id)
    {
        $programa = SisccProgramaComponenteCurricular::findOne($id);

        if(($programa->situacao <> 8) && ($programa->situacao <> 11))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');

        $parecer = SisccParecer::find()->where(['id_programa_componente_curricular' => $id,
            'tipo_parecerista' => SisccParecer::PARECERISTA_CAC,
            'atual' => 1,
        ])->one();

        if ($programa->load(Yii::$app->request->post())) {
            switch($programa->situacao){
                case 8:
                    if($programa->data_aprovacao_coordenacao)
                        $programa->atualizarSituacao();
                break;
                case 11:
                    if(!$programa->data_aprovacao_coordenacao)
                        $programa->situacao -= ($parecer ? 3 : 5);
                    $programa->save();
                break;
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('aprovarprograma', ['model' => $programa, 'parecer' => $parecer]);
    }

    public function actionDefinirparecerista($id)
    {
        $programa = SisccProgramaComponenteCurricular::findOne($id);
        
        if(($programa->situacao < 6) || ($programa->situacao > 10))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');
        
        $model = null;
        $id_pessoa = 0;
        if($programa->situacao >= 7){//se o programa já teve um parecerista emitido
            $model = SisccParecer::find()->where(['id_programa_componente_curricular' => $id,
                'tipo_parecerista' => SisccParecer::PARECERISTA_CAC,
                'atual' => 1,
            ])->one();
            $id_pessoa = $model->id_pessoa;
        }
        else{//caso contrário, cria um novo parecer
            $model = new SisccParecer;
            $model->id_programa_componente_curricular = $id;
            $model->tipo_parecerista = SisccParecer::PARECERISTA_CAC;
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
                        $programa->atualizarSituacao(false, $model->id_pessoa);
                else{
                    $destinatario1 = array();
                    $destinatario2 = array();
                    $pessoa1 = SisrhPessoa::findOne($id_pessoa);
                    if($pessoa1->id_user)
                        $destinatario1[] = "{$pessoa1->user->username}@ufba.br";
                    if(count($pessoa1->emails) > 0)
                        $destinatario1[] = $pessoa1->emails[0];
                    if(count($destinatario1) > 0)
                        Yii::$app->mailer->compose('siscc_mensagem_alteracao_parecerista',['programa' => $programa]) // a view rendering result becomes the message body here
                            ->setFrom('coordenacaoacademica@ufba.br')
                            ->setTo($destinatario1)
                            ->setSubject('SISCC')
                            ->send();
                    $pessoa2 = SisrhPessoa::findOne($model->id_pessoa);
                    if($pessoa2->id_user)
                        $destinatario2[] = "{$pessoa2->user->username}@ufba.br";
                    if(count($pessoa2->emails) > 0)
                        $destinatario2[] = $pessoa2->emails[0];
                    if(count($destinatario2) > 0)
                        Yii::$app->mailer->compose('siscc_mensagens_programa',['programa' => $programa])
                            ->setFrom('coordenacaoacademica@ufba.br')
                            ->setTo($destinatario2)
                            ->setSubject('SISCC')
                            ->send();
                    //Se o parecerista antigo já tiver emitido um parecer...
                    if($model->parecer != null){
                        //o parecer antigo vai deixar de ser o atual...
                        SisccParecer::updateAll(['atual' => 0],"id_parecer = $model->id_parecer");
                        //e uma nova instância é criada id_parecer e parecer nulos.
                        $model->setIsNewRecord(true);
                        $model->id_parecer = null;
                        $model->parecer = null;
                        $model->comentario = null;
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
        $searchModel = new SisccParecerSearch();
        $params = ['id_programa_componente_curricular' => $id, 'tipo_parecerista' => SisccParecer::PARECERISTA_CAC,];
        $dataProvider = $searchModel->search($params);

        return $this->renderPartial('/sisccparecer/impressao', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionResumo()
    {
        $query = new Query;

        $semestre = Yii::$app->request->post('semestre');
        
        $query->select('situacao, count(id_programa_componente_curricular) as total')
            ->from('siscc_programa_componente_curricular')
            ->groupBy('situacao')
            ->orderBy('situacao');
        
        if($semestre)
            $query->where(['id_semestre' => $semestre]);

        $dados = $query->all();

        return $this->render('resumo', ['dados' => $dados, 'semestre' => $semestre]);
    }

}
