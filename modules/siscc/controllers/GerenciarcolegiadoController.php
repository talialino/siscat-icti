<?php

namespace app\modules\siscc\controllers;
use yii\filters\AccessControl;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\modules\siscc\models\SisccProgramaComponenteCurricular;
use app\modules\siscc\models\SisccProgramaComponenteCurricularBibliografia;
use app\modules\siscc\models\SisccProgramaComponenteCurricularSearch;
use app\modules\siscc\models\SisccSemestre;
use app\modules\siscc\models\SisccParecer;
use app\modules\siscc\models\SisccParecerSearch;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisrh\models\SisrhSetor;

class GerenciarcolegiadoController extends \yii\web\Controller
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
                        'allow' => Yii::$app->user->can('sisccGerenciarColegiado'),
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($sort = false)
    {
        $pessoa = Yii::$app->session->get('siscat_pessoa');
        $setorpessoas = $pessoa->getSisrhSetorPessoas()->select('id_setor')->where(['<','funcao',2])->all();
        $setores = [];
        foreach($setorpessoas as $setorpessoa)
            $setores[] = $setorpessoa->id_setor;
        
        $colegiados = SisrhSetor::find()->where(['id_setor' => $setores, 'eh_colegiado' => 1])->all();
        
        $programas = new SisccProgramaComponenteCurricularSearch();
        
        $programas->id_semestre = SisccSemestre::find()->max('id_semestre');

        $programas->id_setor = $setores[0];

        $dataProvider = $programas->search(Yii::$app->request->queryParams,$sort);

        $colegiadoSelecionado = false;
        foreach($colegiados as $c)
            if($c->id_setor == $programas->id_setor){
                $colegiadoSelecionado = $c;
            }
        
        return $this->render('index', [
            'colegiados' => $colegiados,
            'colegiado' => $colegiadoSelecionado,
            'searchModel' => $programas,
            'dataProvider' => $dataProvider]);
    }

    public function actionAprovarprograma($id)
    {
        $programa = SisccProgramaComponenteCurricular::findOne($id);

        $pessoa = Yii::$app->session->get('siscat_pessoa');

        if(!$pessoa->getSisrhSetorPessoas()->where(['id_setor' => $programa->id_setor])->andWhere(['<','funcao',2])->exists())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        if(($programa->situacao <> 3) && ($programa->situacao <> 6))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');

        $parecer = SisccParecer::find()->where(['id_programa_componente_curricular' => $id,
            'tipo_parecerista' => SisccParecer::PARECERISTA_COLEGIADO,
            'atual' => 1,
        ])->one();

        if ($programa->load(Yii::$app->request->post())) {
            switch($programa->situacao){
                case 3:
                    if($programa->data_aprovacao_colegiado)
                        $programa->atualizarSituacao();
                break;
                case 6:
                    if(!$programa->data_aprovacao_colegiado)
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
        
        if(($programa->situacao < 1) || ($programa->situacao > 5))
            throw new ForbiddenHttpException('Você não pode realizar essa operação.');
        
        $pessoa = Yii::$app->session->get('siscat_pessoa');

        if(!$pessoa->getSisrhSetorPessoas()->where(['id_setor' => $programa->id_setor])->andWhere(['<','funcao',2])->exists())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');

        $model = null;
        $id_pessoa = 0;
        if($programa->situacao >= 2){//se o programa já teve um parecerista emitido
            $model = SisccParecer::find()->where(['id_programa_componente_curricular' => $id,
                'tipo_parecerista' => SisccParecer::PARECERISTA_COLEGIADO,
                'atual' => 1,
            ])->one();
            $id_pessoa = $model->id_pessoa;
        }
        else{//caso contrário, cria um novo parecer
            $model = new SisccParecer;
            $model->id_programa_componente_curricular = $id;
            $model->tipo_parecerista = SisccParecer::PARECERISTA_COLEGIADO;
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

            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $t) {
                $transaction->rollBack();
                throw $t;
            }
            return $this->redirect(Yii::$app->request->referrer);
         }
        return $this->renderAjax('definirparecerista', ['model' => $model, 'id_setor' => $programa->id_setor]);
    }

    public function actionParecer($id)
    {
        $programa = SisccProgramaComponenteCurricular::findOne($id);

        $pessoa = Yii::$app->session->get('siscat_pessoa');

        if(!$pessoa->getSisrhSetorPessoas()->where(['id_setor' => $programa->id_setor])->andWhere(['<','funcao',2])->exists())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');
        
        $searchModel = new SisccParecerSearch();
        $params = ['id_programa_componente_curricular' => $id, 'tipo_parecerista' => SisccParecer::PARECERISTA_COLEGIADO,
        ];
        $dataProvider = $searchModel->search($params);

        return $this->renderPartial('/sisccparecer/impressao', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEditarprograma($id, $showAbaBibliografia = false)
    {
        $programa = SisccProgramaComponenteCurricular::findOne($id);

        if($programa == null)
            throw new NotFoundHttpException('A página solicitada não foi encontrada.');

        $pessoa = Yii::$app->session->get('siscat_pessoa');

        if(($programa->situacao >= 6 && $programa->situacao != 9) || !$pessoa->getSisrhSetorPessoas()->where(['id_setor' => $programa->id_setor])->andWhere(['<','funcao',2])->exists())
            throw new ForbiddenHttpException('Você não está autorizado a acessar essa página.');
        
            if(!$programa->objetivos_especificos)
            $programa->objetivos_especificos = '<ul><li>&nbsp;</li></ul>';

        if(!$programa->conteudo_programatico)
            $programa->conteudo_programatico = '<ul><li>&nbsp;</li></ul>';

        $dataProvider = new ActiveDataProvider([
            'query' => $programa->getSisccProgramaComponenteCurricularBibliografias(),
            'pagination' => false,
        ]);

        if ($programa->load(Yii::$app->request->post()) && $programa->save())
            $showAbaBibliografia = true;

        /**
         * objetivos_especificos e conteudo_programatico utilizam formato de lista
         * Nas linhas a seguir, caso o campo esteja vazio
         */

        return $this->render('editar', [
            'model' => $programa,
            'dataProvider' => $dataProvider,
            'showAbaBibliografia' => $showAbaBibliografia,
        ]);
    }

    /**
     * O método a seguir serve para excluir as referências bibliográficas, caso seja realizada pela coordenação do colegiado
     */
    public function actionDeletebibliografia($id_programa_componente_curricular, $id_bibliografia)
    {
        SisccProgramaComponenteCurricularBibliografia::findOne([
            'id_programa_componente_curricular' => $id_programa_componente_curricular,
            'id_bibliografia' => $id_bibliografia])->delete();

        return $this->redirect(['editarprograma', 'id' => $id_programa_componente_curricular, 'showAbaBibliografia' => true]);
    }

}