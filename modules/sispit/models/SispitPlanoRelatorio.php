<?php

namespace app\modules\sispit\models;

use Yii;

use  dektrium\user\models\User;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sispit\config\LimiteCargaHoraria;
use app\modules\sispit\models\SispitEnsinoComponenteSearch;
use app\modules\sispit\models\SispitAtividadeComplementar;
use app\modules\sispit\models\SispitOrientacaoAcademicaSearch;
use app\modules\sispit\models\SispitMonitoriaSearch;
use app\modules\sispit\models\SispitEnsinoOrientacaoSearch;
use app\modules\sispit\models\SispitAtividadesAdministrativasSearch;
use app\modules\sispit\models\SispitAfastamentoDocenteSearch;
use app\modules\sispit\models\SispitParticipacaoEventoSearch;
use app\modules\sispit\models\SispitPesquisaExtensaoSearch;
use app\modules\sispit\models\SispitLigaAcademicaSearch;
use app\modules\sispit\models\SispitPublicacaoSearch;
use yii\db\Query;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "sispit_plano_relatorio".
 *
 * O campo situacao_estagio_probatorio serve para tratar dos rit parciais que os docentes
 *  em estágio probatório precisam entregar e que são avaliados diretamente pela CAC.
 * Os rits parciais são aqueles cujo 2º semestre não está preenchido.
 * A tramitação desses rits parciais ocorrem diferente do rit tradicional, visto
 * que eles não passam pelo núcleo, indo direto para a CAC.
 * Será disponibilizado ao docente um botão para retornar o status do plano_relatorio
 * retorna para 10 (rit não preenchido ou submetido para avaliação)
 * de forma que o professor possa preencher o segundo semestre.
 *
 * @property int $id_plano_relatorio
 * @property int $user_id
 * @property int $id_ano
 * @property int $observacao_pit
 * @property int $observacao_rit
 * @property int $justificativa
 * @property string $data_homologacao_nucleo_pit
 * @property string $data_homologacao_cac_pit
 * @property string $data_preenchimento_pit 	
 * @property string $data_homologacao_nucleo_rit
 * @property string $data_homologacao_cac_rit
 * @property string $data_preenchimento_rit 	
 * @property int $status
 * @property int $situacao_estagio_probatorio
 *
 * @property SispitAfastamentoDocente[] $sispitAfastamentoDocentes
 * @property SispitAtividadeComplementar $sispitAtividadeComplementar
 * @property SispitAtividadesAdministrativas[] $sispitAtividadesAdministrativas
 * @property SispitEnsinoComponente[] $sispitEnsinoComponentes
 * @property SispitEnsinoOrientacao[] $sispitEnsinoOrientacaos
 * @property SispitEstagioProbatorio[] $sispitEstagioProbatorios
 * @property SispitMonitoria[] $sispitMonitorias
 * @property SispitOrientacaoAcademica[] $sispitOrientacaoAcademicas
 * @property SispitParecer[] $sispitParecers
 * @property SispitParticipacaoEvento[] $sispitParticipacaoEventos
 * @property SispitPesquisaExtensao[] $sispitPesquisaExtensaos
 * @property SispitLigaAcademica[] $sispitLigaAcademicas
 * @property SispitAno $id_ano
 * @property User $pessoa
 * @property SispitPublicacao[] $sispitPublicacaos
 */
class SispitPlanoRelatorio extends \yii\db\ActiveRecord
{
    public const SITUACAO = [
        0 => 'PIT não preenchido ou não submetido para avaliação',
        1 => 'PIT preenchido, aguardando o núcleo definir o parecerista',
        2 => 'PIT aguardando avaliação do parecerista do núcleo',
        3 => 'PIT aprovado pelo parecerista do núcleo',
        4 => 'PIT necessita de correções de acordo com parecerista do núcleo',
        5 => 'PIT aguardando a CAC definir parecerista',
        6 => 'PIT aguardando avaliação do parecerista da CAC',
        7 => 'PIT aprovado pelo parecerista da CAC',
        8 => 'PIT necessita de correções de acordo com parecerista da CAC',
        9 => 'PIT homologado pela CAC',
        10 => 'RIT não preenchido ou não submetido para avaliação',
        11 => 'RIT preenchido, aguardando o núcleo definir o parecerista',
        12 => 'RIT aguardando avaliação do parecerista do núcleo',
        13 => 'RIT aprovado pelo parecerista do núcleo',
        14 => 'RIT necessita de correções de acordo com parecerista do núcleo',
        15 => 'RIT aguardando a CAC definir parecerista',
        16 => 'RIT aguardando avaliação do parecerista da CAC',
        17 => 'RIT aprovado pelo parecerista da CAC',
        18 => 'RIT necessita de correções de acordo com parecerista da CAC',
        19 => 'RIT homologado pela CAC',
    ];

    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sispit_plano_relatorio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'id_ano'], 'required'],
            [['user_id', 'id_ano', 'status','id_plano_relatorio', 'situacao_estagio_probatorio'], 'integer'],
            [['observacao_pit','observacao_rit','justificativa'],'string'],
            [['data_homologacao_nucleo_pit', 'data_homologacao_cac_pit', 'data_preenchimento_pit', 'data_homologacao_nucleo_rit', 'data_homologacao_cac_rit', 'data_preenchimento_rit'], 'safe'],
            [['id_ano', 'user_id'], 'unique', 'targetAttribute' => ['id_ano', 'user_id']],
            [['id_ano'], 'exist', 'skipOnError' => true, 'targetClass' => SispitAno::class, 'targetAttribute' => ['id_ano' => 'id_ano']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'Docente',
            'id_ano' => 'Ano',
            'observacao_pit' => 'Observações',
            'observacao_rit' => 'Observações',
            'justificativa' => 'Justificativa',
            'data_homologacao_nucleo_pit' => 'Data de homologação Núcleo',
            'data_homologacao_cac_pit' => 'Data de homologacao Coordenação Acadêmica',
            'data_preenchimento_pit' => 'Data de preenchimento',
            'data_homologacao_nucleo_rit' => 'Data de homologação Núcleo',
            'data_homologacao_cac_rit' => 'Data de homologação Coordenação Acadêmica',
            'data_preenchimento_rit' => 'Data de preenchimento',
            'status' => 'Situação',
            'situacao' => 'Situação',
            'situacao_estagio_probatorio' => 'Situação - Estágio Probatório',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitAfastamentoDocentes()
    {
        return $this->hasMany(SispitAfastamentoDocente::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitAtividadeComplementar()
    {
        return $this->hasOne(SispitAtividadeComplementar::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitAtividadesAdministrativas()
    {
        return $this->hasMany(SispitAtividadesAdministrativas::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitEnsinoComponentes()
    {
        return $this->hasMany(SispitEnsinoComponente::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitEnsinoOrientacaos()
    {
        return $this->hasMany(SispitEnsinoOrientacao::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitMonitorias()
    {
        return $this->hasMany(SispitMonitoria::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitOrientacaoAcademicas()
    {
        return $this->hasMany(SispitOrientacaoAcademica::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitParecers()
    {
        return $this->hasMany(SispitParecer::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitParticipacaoEventos()
    {
        return $this->hasMany(SispitParticipacaoEvento::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitPesquisaExtensaos()
    {
        return $this->hasMany(SispitPesquisaExtensao::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitLigaAcademicas()
    {
        return $this->hasMany(SispitLigaAcademica::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitAno()
    {
        return $this->hasOne(SispitAno::class, ['id_ano' => 'id_ano']);
    }

    public function getAno()
    {
        if($this->id_ano === null)
            return null;
        return $this->sispitAno->string;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoa()
    {
        return SisrhPessoa::find()->where(['id_user' => $this->user_id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitPublicacaos()
    {
        return $this->hasMany(SispitPublicacao::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    /**
     * Retorna o PlanoRelatorio referente ao usuário e ano especificados
     * @return SispitPlanoRelatorio
     */
    public static function getPlanoRelatorio($user_id, $id_ano)
    {
        if(!$user_id || !$id_ano)
            return null;
        return parent::findOne(['user_id' => $user_id, 'id_ano' => $id_ano]);
    }

    /**
     * Retorna a situação do pit/rit atual em formato string
     * @return string
     */
    public function getSituacao()
    {
        if($this->status === null)
            return null;
        $saida = $this::SITUACAO[$this->status];
        //Verifica se a situação atual se refere ao rit e se ele é parcial
        if($this->status > 10 && $this->situacao_estagio_probatorio > 0 && $this->sispitAno->suplementar == 0)
            $saida = str_replace('RIT', 'RIT PARCIAL', $saida);
        return $saida;
    }

    public static function getSituacaoPlanoRelatorio($id_plano_relatorio)
    {
        return self::findOne($id_plano_relatorio)->situacao;
    }

    /**
     * @return boolean
     */
    public function isPitEditable()
    {
        return $this->status == 0 || $this->status == 4 || $this->status == 8;
    }

    /**
     * @return boolean
     */
    public function isRitEditable()
    {
        return $this->status == 10 || $this->status == 14 || $this->status == 18;
    }

    /**
     * @return boolean
     */
    public function isEditable()
    {
        if(!Yii::$app->user->can('sispitGerenciar',['id' => $this->user_id]))
            return false;
        return $this->isPitEditable() || $this->isRitEditable();
    }

    /**
     * @return boolean
     */
    public function isRitAvailable()
    {
        return $this->status > 9;
    }

    public function getTotal($pit_rit)
    {
        $componentes = $this->formatarCHTotal(
             Yii::$app->db->createCommand("SELECT semestre, SUM(COALESCE(ch_teorica,0) + COALESCE(ch_pratica,0) + COALESCE(ch_estagio,0))
             AS total FROM sispit_ensino_componente WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $pit_rit GROUP BY semestre")->queryAll());

        if($this->sispitAtividadeComplementar != null){
            $atividadeComplementar = [
                [ 'semestre' => 1, 'total' => !$pit_rit ? 
                    $this->sispitAtividadeComplementar->ch_graduacao_sem1_pit + $this->sispitAtividadeComplementar->ch_pos_sem1_pit : 
                    $this->sispitAtividadeComplementar->ch_graduacao_sem1_rit + $this->sispitAtividadeComplementar->ch_pos_sem1_rit
                ], 
                [ 'semestre' => 2, 'total' => !$pit_rit ? 
                    $this->sispitAtividadeComplementar->ch_graduacao_sem2_pit + $this->sispitAtividadeComplementar->ch_pos_sem2_pit : 
                    $this->sispitAtividadeComplementar->ch_graduacao_sem2_rit + $this->sispitAtividadeComplementar->ch_pos_sem2_rit
                ],  
            ];
            $orientacaoAcademica = [
                [ 'semestre' => 1, 'total' => 0 + (!$pit_rit ? 
                    $this->sispitAtividadeComplementar->ch_orientacao_academica_sem1_pit : $this->sispitAtividadeComplementar->ch_orientacao_academica_sem1_rit)
                ], 
                [ 'semestre' => 2, 'total' => 0 + (!$pit_rit ? 
                    $this->sispitAtividadeComplementar->ch_orientacao_academica_sem2_pit : $this->sispitAtividadeComplementar->ch_orientacao_academica_sem2_rit)
                ],  
            ];
        }
        else{
            $atividadeComplementar = [['semestre' => 1, 'total' => 0],['semestre' => 2, 'total' => 0]];
            $orientacaoAcademica = [['semestre' => 1, 'total' => 0],['semestre' => 2, 'total' => 0]];
        }

        $monitoria = $this->formatarCHTotal(
             Yii::$app->db->createCommand("SELECT semestre, SUM(COALESCE(carga_horaria,0)) AS total FROM sispit_monitoria WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $pit_rit GROUP BY semestre")->queryAll());

        $orientacao = $this->formatarCHTotal(
             Yii::$app->db->createCommand("SELECT semestre, SUM(COALESCE(carga_horaria,0)) AS total FROM sispit_ensino_orientacao WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $pit_rit GROUP BY semestre")->queryAll());
        
        $liga = $this->formatarCHTotal(
            Yii::$app->db->createCommand("SELECT semestre, SUM(COALESCE(carga_horaria,0)) AS total FROM sispit_liga_academica WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $pit_rit GROUP BY semestre")->queryAll());

        $pesquisaex = $this->formatarCHTotal(
             Yii::$app->db->createCommand("SELECT semestre, SUM(COALESCE(carga_horaria,0)) AS total FROM sispit_pesquisa_extensao WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $pit_rit GROUP BY semestre")->queryAll());

        $administracao = $this->formatarCHTotal(
             Yii::$app->db->createCommand("SELECT semestre, SUM(COALESCE(carga_horaria,0)) AS total FROM sispit_atividades_administrativas WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $pit_rit GROUP BY semestre")->queryAll());

        $afastamento = $this->formatarCHTotal(
             Yii::$app->db->createCommand("SELECT semestre, SUM(COALESCE(carga_horaria,0)) AS total FROM sispit_afastamento_docente WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $pit_rit AND eh_afastamento = 1 GROUP BY semestre")->queryAll());

        $outrasOcorrencias = $this->formatarCHTotal(
            Yii::$app->db->createCommand("SELECT semestre, SUM(COALESCE(carga_horaria,0)) AS total FROM sispit_afastamento_docente WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $pit_rit AND eh_afastamento = 0 GROUP BY semestre")->queryAll());

        $total = [
            ['semestre' => 1,
            'total' => $componentes[0]['total'] + $atividadeComplementar[0]['total'] + $orientacaoAcademica[0]['total'] + $monitoria[0]['total'] + $orientacao[0]['total'] + $pesquisaex[0]['total'] + $liga[0]['total'] + $administracao[0]['total'] + $afastamento[0]['total'] + $outrasOcorrencias[0]['total'],
            ],['semestre' => 2,
            'total' => $componentes[1]['total'] + $atividadeComplementar[1]['total'] + $orientacaoAcademica[1]['total'] + $monitoria[1]['total'] + $orientacao[1]['total'] + $pesquisaex[1]['total'] + $liga[1]['total'] + $administracao[1]['total'] + $afastamento[1]['total'] + $outrasOcorrencias[1]['total'],
        ]];

        return [
            'Disciplinas Ministradas' => $componentes,
            'Atividade Complementar' => $atividadeComplementar,
            'Orientação Acadêmica' => $orientacaoAcademica,
            'Orientação de Monitoria' => $monitoria,
            'Outras Orientações' => $orientacao,
            'Liga Acadêmica' => $liga,
            'Pesquisa e Extensão' => $pesquisaex,
            'Atividades de Administração' => $administracao,
            'Afastamento' => $afastamento,
            'Outras Ocorrências' => $outrasOcorrencias,
            'Total' => $total];
    }

    /**
     * Ajusta os totais de carga horária de cada um dos itens do pit/rit para o formato:
     * [['semestre' => 1, 'total' => <valor>],['semestre' => 2, 'total' => <valor>]]
     */
    protected function formatarCHTotal($array){
        switch(count($array)){
            case 0:
                return [['semestre' => 1, 'total' => 0],['semestre' => 2, 'total' => 0]];
            case 1:
                if($array[0]['semestre'] == 1)
                    return [$array[0],['semestre' =>  2, 'total' => 0]];
                return [['semestre' =>  1, 'total' => 0], $array[0]];
            case 2:
                return $array;
        }
    }

    public function atualizarStatus($aprovado = false, $parecerista = false)
    {
        if($this->status == 19)
            return false;
        
        // Os comandos a seguir definem a data de preenchimento, seja para o pit, se o status
        // for menor que 9, ou para o rit, se for igual a 10.
        // Esses comandos foram adicionados no dia 09.04.2021, ou seja, depois da primeira parte
        // do sispit, por isso, usei a comparação ser for menor que 9 para o pit a fim de definir
        // uma data de preenchimento para aqueles pits que não tiveram a data definida antes,
        // e que realizarem alguma modificação em sua estrutura.

        if($this->status < 9 && !$this->data_preenchimento_pit)
            $this->data_preenchimento_pit = date('Y-m-d');
        if($this->status == 10)
            $this->data_preenchimento_rit = date('Y-m-d');
        
        
        if(!$aprovado)
            switch($this->status % 10){
                case 4:
                case 8:
                    $this->status -= 2;
                break;
                case 3:
                case 7:
                    $this->status += 2;
                break;
                default:
                    $this->status++;
            }
        $pessoa = false;
        $destinatarios = array();
        switch($this->status % 10){
            case 1:
            case 3:
                $query = new Query();
                $query->select('c.id_pessoa')
                ->from('sisrh_pessoa p, sisrh_pessoa c, sisrh_setor_pessoa sp, sisrh_setor_pessoa sp2, sisrh_setor s')
                ->where("p.id_pessoa = {$this->pessoa->id_pessoa} AND p.id_pessoa = sp.id_pessoa AND sp.id_setor = s.id_setor AND s.eh_nucleo_academico = 1 AND sp.id_setor = sp2.id_setor AND sp2.id_pessoa = c.id_pessoa AND sp2.funcao = 0");
                $result = $query->one();
                if($result)
                    $pessoa = SisrhPessoa::findOne($result['id_pessoa']);
            break;
            case 2:
            case 6:
                if($parecerista)
                    $pessoa = SisrhPessoa::findOne($parecerista);
            break;
            case 4:
            case 8:
            case 9:
                $pessoa = SisrhPessoa::find()->where(['id_user' => $this->user_id])->one();
            break;
            case 5:
            case 7:
                $destinatarios[] = 'coordenacaoacademica@ufba.br';
            break;
        }
        if($pessoa){
            if($pessoa->id_user)
                $destinatarios[] = "{$pessoa->user->username}@ufba.br";
            if(count($pessoa->emails) > 0)
                $destinatarios[] = $pessoa->emails[0];
        }
        if($this->save()){
            if(count($destinatarios) > 0)
                Yii::$app->mailer->compose('sispit_mensagens_plano_relatorio',['plano' => $this]) // a view rendering result becomes the message body here
                    ->setFrom('coordenacaoacademica@ufba.br')
                    ->setTo($destinatarios)
                    ->setSubject('SISPIT')
                    ->send();
            return true;
        }
        return false;
    }

    public function validarCargaHoraria($pit_rit = null)
    {
        if($pit_rit === null)
            $pit_rit = $this->isRitAvailable() ? 1 : 0;
        
        $totalArray = $this->getTotal($pit_rit);

        $pessoa = SisrhPessoa::find()->where(['id_user' => $this->user_id])->one();
        $total = $totalArray['Total'];
        $limite = $pessoa->regime_trabalho;
        if($pessoa->regime_trabalho == null)
            throw new BadRequestHttpException("O seu regime de trabalho não está definido no sistema. Por favor, entre em contato com a CGDP através do e-mail cgdp.ims@ufba.br para atualizar seu cadastro.");
        if($limite > 40)
            $limite = 40;

        $componentes = $totalArray['Disciplinas Ministradas'];
        $administracao = $totalArray['Atividades de Administração'];
        $afastamento = $totalArray['Afastamento'];
        $outrasOcorrencias = $totalArray['Outras Ocorrências'];
        $limiteComponentes = [1 => LimiteCargaHoraria::LIMITES['ensino_componente'][$pessoa->regime_trabalho],
                2 => LimiteCargaHoraria::LIMITES['ensino_componente'][$pessoa->regime_trabalho]];
        if($this->id_ano == 1) //Por solicitação de Tiana, foi removido o limite mínimo para o ano de 2020
            $limiteComponentes = [1 => 0, 2 => 0];
        else
            foreach($limiteComponentes as $k => $v){
                if($administracao[$k-1]['total'] > 0){
                    $limiteComponentes[$k] = $administracao[$k-1]['total'] == 40 ? 0 : 
                        LimiteCargaHoraria::LIMITES['ensino_componente'][0];
                }
                if(($afastamento[$k-1]['total'] + $outrasOcorrencias[$k-1]['total']) >= $limite - $limiteComponentes[$k]){
                    $limiteComponentes[$k] = 0;
                }
            }

        $atividadeComplementarLabels = [
            'ch_graduacao_sem1_pit' => 'A carga horária de graduação de Atividade Complementar do 1º semestre',
            'ch_pos_sem1_pit' => 'A carga horária de pós-graduação de Atividade Complementar do 1º semestre',
            'ch_graduacao_sem2_pit' => 'A carga horária de graduação de Atividade Complementar do 2º semestre',
            'ch_pos_sem2_pit' => 'A carga horária de pós-graduação de Atividade Complementar do 2º semestre',
            'ch_graduacao_sem1_rit' => 'A carga horária de graduação de Atividade Complementar do 1º semestre',
            'ch_pos_sem1_rit' => 'A carga horária de pós-graduação de Atividade Complementar do 1º semestre',
            'ch_graduacao_sem2_rit' => 'A carga horária de graduação de Atividade Complementar do 2º semestre',
            'ch_pos_sem2_rit' => 'A carga horária de pós-graduação de Atividade Complementar do 2º semestre',
            'ch_orientacao_academica_sem1_pit' => 'A carga horária de Orientação Acadêmica 1º semestre',
            'ch_orientacao_academica_sem2_pit' => 'A carga horária de Orientação Acadêmica 2º semestre',
            'ch_orientacao_academica_sem1_rit' => 'A carga horária de Orientação Acadêmica 1º semestre',
            'ch_orientacao_academica_sem2_rit' => 'A carga horária de Orientação Acadêmica 2º semestre',
        ];

        $atividadeComplementar = $this->sispitAtividadeComplementar;
        $atividadeComplementar->scenario = $pit_rit ? SispitAtividadeComplementar::SCENARIO_RIT : SispitAtividadeComplementar::SCENARIO_PIT;
        $atividadeComplementar->validate();
        $atvErros = $atividadeComplementar->getErrors();

        $erros = array();

        foreach($componentes as $value)
            if($value['total'] < $limiteComponentes[$value['semestre']])
                $erros[] = "A carga horária de componentes ministrados do {$value['semestre']}º semestre não pode ser inferior a {$limiteComponentes[$value['semestre']]}.";

        foreach($atvErros as $k => $v){
            foreach($v as $erro)
                $erros[] = "{$atividadeComplementarLabels[$k]} apresentou o seguinte erro: $erro.";
        }

        foreach($total as $value)
            if($value['total'] <> $limite)
                $erros[] = "A carga horária total do {$value['semestre']}º semestre ({$value['total']}) não corresponde a sua carga horária semanal ($limite).";

        $this->addErrors(['id_plano_relatorio' => $erros]);

        return count($erros) == 0;
    }

    public function planoRelatorioToArray($pit_rit = null)
    {
        if($pit_rit === null)
            $pit_rit = $this->isRitAvailable() ? 1 : 0;

        $id = $this->id_plano_relatorio;
        
        $ensinoComponente = new SispitEnsinoComponenteSearch();
        $ensinoComponente->id_plano_relatorio = $id;
        $ensinoComponente->pit_rit = $pit_rit;
        $orientacaoAcademica = new SispitOrientacaoAcademicaSearch;
        $orientacaoAcademica->id_plano_relatorio = $id;
        $orientacaoAcademica->pit_rit = $pit_rit;
        $monitoria = new SispitMonitoriaSearch;
        $monitoria->id_plano_relatorio = $id;
        $monitoria->pit_rit = $pit_rit;
        $ensinoOrientacao = new SispitEnsinoOrientacaoSearch;
        $ensinoOrientacao->id_plano_relatorio = $id;
        $ensinoOrientacao->pit_rit = $pit_rit;
        $atividadesAdministrativas = new SispitAtividadesAdministrativasSearch();
        $atividadesAdministrativas->id_plano_relatorio = $id;
        $atividadesAdministrativas->pit_rit = $pit_rit;
        $afastamentoDocente = new SispitAfastamentoDocenteSearch();
        $afastamentoDocente->id_plano_relatorio = $id;
        $afastamentoDocente->pit_rit = $pit_rit;
        $afastamentoDocente->eh_afastamento = 1;
        $outrasOcorrencias = new SispitAfastamentoDocenteSearch();
        $outrasOcorrencias->id_plano_relatorio = $id;
        $outrasOcorrencias->pit_rit = $pit_rit;
        $outrasOcorrencias->eh_afastamento = 0;
        $participacaoEventos = new SispitParticipacaoEventoSearch();
        $participacaoEventos->id_plano_relatorio = $id;
        $participacaoEventos->pit_rit = $pit_rit;
        $pesquisaExtensao = new SispitPesquisaExtensaoSearch();
        $pesquisaExtensao->id_plano_relatorio = $id;
        $pesquisaExtensao->pit_rit = $pit_rit;
        $liga = new SispitLigaAcademicaSearch();
        $liga->id_plano_relatorio = $id;
        $liga->pit_rit = $pit_rit;
        $publicacao = new SispitPublicacaoSearch();
        $publicacao->id_plano_relatorio = $id;
        $publicacao->pit_rit = $pit_rit;

        return [
            'pit_rit' => $pit_rit,
            'model' => $this,
            'ensinoComponente' => $ensinoComponente,
            'atividadeComplementar' => $this->sispitAtividadeComplementar,
            'orientacaoAcademica' => $orientacaoAcademica,
            'monitoria'=> $monitoria,
            'ensinoOrientacao' => $ensinoOrientacao,
            'atividadesAdministrativas' =>$atividadesAdministrativas,
            'afastamentoDocente' =>$afastamentoDocente,
            'outrasOcorrencias' =>$outrasOcorrencias,
            'participacaoEventos' =>$participacaoEventos,
            'pesquisaExtensao' =>$pesquisaExtensao,
            'ligaAcademica' =>$liga,
            'publicacao' =>$publicacao,
        ];
    }

    /**
     * Esse método é utilizado na tela de view para verificar se pode exibir o botão de submeter
     * rit parcial para estágio probatório
     * Essa função retornará verdadeiro, se não houver erro no primeiro semestre
     * e o rit estiver em estágio probatório
     * @return boolean
     */
    public function exibirBotaoSubmeterRitParcial()
    {
        if(!$this->isRitEditable() || ($this->situacao_estagio_probatorio != 1))
            return false;
        foreach($this->getErrors('id_plano_relatorio') as $erro){
            if(strpos($erro, '1º semestre') !== false)
                return false;
        }

        return true;
    }

    /**
     * Informa se o RIT é parcial. Isso é utilizado nas telas de exibição (_view e _pdf)
     * @return boolean
     */
    public function ehRitParcial()
    {
        return ($this->status >= 10 && $this->situacao_estagio_probatorio == 1);
    }
}