<?php

namespace app\modules\sisape\models;

use Yii;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisrh\models\SisrhSetor;


/**
 * This is the model class for table "sisape_projeto".
 *
 * @property int $id_projeto
 * @property int $id_pessoa
 * @property int $tipo_projeto
 * @property string $titulo
 * @property string $area_atuacao
 * @property string $local_execucao
 * @property string $parcerias
 * @property string $infraestrutura
 * @property int $submetido_etica
 * @property int $disponivel_site
 * @property string $resumo
 * @property string $introducao
 * @property string $justificativa
 * @property string $objetivos
 * @property string $metodologia
 * @property string $resultados_esperados
 * @property int $situacao
 * @property string $data_inicio
 * @property string $data_fim 	
 * @property string $orcamento
 * @property string $referencias
 * @property int $id_setor
 * @property string $data_aprovacao_nucleo
 * @property string $data_aprovacao_copex
 * @property string $data_homologacao_congregacao
 * @property int $sessao_congregacao
 * @property int $tipo_sessao_congregacao
 * @property int $tipo_extensao
 *
 * @property SisapeAtividade[] $sisapeAtividades
 * @property SisapeFinanciamento[] $sisapeFinanciamentos
 * @property SisapeParecer[] $sisapeParecers
 * @property SisrhPessoa $pessoa
 * @property SisapeProjetoIntegrante[] $sisapeProjetoIntegrantes
 * @property SisapeRelatorio[] $sisapeRelatorios
 */

 /*
    Em 20/09/2021 foi removida a tramitação de avaliações (com pareceres) tanto do núcleo quanto da COPEX, a pedido da própria COPEX.
    Deixamos a estrutura inalterada para caso futuramente seja necessário o seu retorno.
 */
class SisapeProjeto extends \yii\db\ActiveRecord
{
    public const PESQUISA = 1;
    public const EXTENSAO = 2;

    public const TIPO_PROJETO = [
        self::PESQUISA => 'Pesquisa',
        self::EXTENSAO => 'Extensão',
    ];

    public const TIPO_EXTENSAO = [
        0 => '',
        1 => 'Eventual',
        2 => 'Permanente com relatórios semestrais',
        3 => 'Permanente com relatórios anuais',
    ];

    public const TIPO_SESSAO_CONGREGACAO = [
        1 => 'Ordinária',
        2 => 'Extraordinária',
    ];

    public const SITUACAO = [
        0 => 'Projeto não preenchido',
        //1 => 'Projeto preenchido, aguardando o núcleo definir o parecerista',
        //2 => 'Projeto aguardando avaliação do parecerista do núcleo',
        //3 => 'Projeto aprovado pelo parecerista do núcleo',
        //4 => 'Projeto necessita de correções de acordo com parecerista do núcleo',
        //5 => 'Projeto corrigido e aguardando reavaliação do parecerista do núcleo',
        //6 => 'Projeto aprovado pelo núcleo, aguardando a COPEX definir parecerista',
        //7 => 'Projeto aguardando avaliação do parecerista da COPEX',
        //8 => 'Projeto aprovado pelo parecerista da COPEX',
        //9 => 'Projeto necessita de correções de acordo com parecerista da COPEX',
        //10 => 'Projeto corrigido e aguardando reavaliação do parecerista da COPEX',
        16 => 'Projeto preenchido aguardando aprovação da COPEX',
        11 => 'Projeto aprovado pela COPEX',
        12 => 'Projeto homologado pela Congregação',
        13 => 'Projeto não homologado',
        14 => 'Projeto homologado e relatório apresentado',
        //15 => 'Projeto preenchido, aguardando COPEX definir o núcleo',
        //16 => 'Projeto importado aguardando COPEX definir situação',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisape_projeto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pessoa', 'tipo_projeto', 'disponivel_site','titulo','resumo', 'data_inicio'], 'required'],  
            [['area_atuacao'], 'required', 'when' => function($model) {
                    return $model->isEditable();
                }, 'whenClient' => "function (attribute, value) {
                    return ($('#sisapeprojeto-situacao').val() == 0 || $('#sisapeprojeto-situacao').val() == 1 || $('#sisapeprojeto-situacao').val() == 4 || $('#sisapeprojeto-situacao').val() == 9 || $('#sisapeprojeto-situacao').val() == 15);
                }"
            ],
            [['data_fim'], 'required', 'when' => function($model) {
                    return !($model->tipo_projeto == self::EXTENSAO && $model->tipo_extensao > 1);
                }, 'whenClient' => "function (attribute, value) {
                    return !($('#sisapeprojeto-tipo_projeto').val() == ".self::EXTENSAO." && $('#sisapeprojeto-tipo_extensao').val() > 1);
                }"
            ],
            [['id_pessoa', 'tipo_projeto', 'submetido_etica', 'disponivel_site', 'situacao', 'id_setor', 'sessao_congregacao', 'tipo_sessao_congregacao', 'tipo_extensao'], 'integer'],
            [['titulo', 'area_atuacao', 'local_execucao', 'parcerias', 'infraestrutura', 'resumo', 'introducao', 'justificativa', 'objetivos', 'metodologia', 'resultados_esperados', 'orcamento', 'referencias'], 'string'],
            [['data_inicio', 'data_fim', 'data_aprovacao_nucleo', 'data_aprovacao_copex', 'data_homologacao_congregacao'], 'safe'],
            [['id_pessoa'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhPessoa::class, 'targetAttribute' => ['id_pessoa' => 'id_pessoa']],
            [['id_setor'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhSetor::class, 'targetAttribute' => ['id_setor' => 'id_setor']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_projeto' => 'Projeto',
            'numero' => 'Número da Proposta',
            'id_pessoa' => 'Coordenador(a)',
            'pessoa.nome' => 'Coordenador(a)',
            'tipo_projeto' => 'Tipo de Projeto',
            'tipoProjeto' => 'Tipo de Projeto',
            'titulo' => 'Título',
            'area_atuacao' => 'Área do Conhecimento',
            'local_execucao' => 'Local da Execução',
            'parcerias' => 'Parcerias',
            'infraestrutura' => 'Infraestrutura Necessária e Material de Consumo',
            'submetido_etica' => 'Submetido ao Comitê de Ética (se for o caso)',
            'disponivel_site' => 'Autoriza veiculação do resumo no site do IMS',
            'resumo' => 'Resumo',
            'introducao' => 'Introdução',
            'justificativa' => 'Justificativa',
            'objetivos' => 'Objetivos',
            'metodologia' => 'Metodologia',
            'resultados_esperados' => 'Resultados Esperados',
            'situacao' => 'Situação',
            'situacaoString' => 'Situação',
            'data_inicio' => 'Data Inicial',
            'data_fim' => 'Data Final',
            'orcamento' => 'Orçamento',
            'referencias' => 'Referências',
            'id_setor' => 'Núcleo Responsável',
            'data_aprovacao_nucleo' => 'Data de Aprovação pelo Núcleo',
            'data_aprovacao_copex' => 'Data de Aprovação (ou não homologação) pela COPEX',
            'data_homologacao_congregacao' => 'Data de Homologação Congregação',
            'sessao_congregacao' => 'Nº da Sessão Congregação',
            'tipo_sessao_congregacao' => 'Tipo de Sessão da Congregação',
            'tipoSessaoCongregacao' => 'Tipo de Sessão da Congregação',
            'tipo_extensao' => 'Tipo de Extensão',
            'tipoExtensao' => 'Tipo de Extensão',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisapeAtividades()
    {
        return $this->hasMany(SisapeAtividade::class, ['id_projeto' => 'id_projeto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisapeFinanciamentos()
    {
        return $this->hasMany(SisapeFinanciamento::class, ['id_projeto' => 'id_projeto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisapePareceres()
    {
        return $this->hasMany(SisapeParecer::class, ['id_projeto' => 'id_projeto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoa()
    {
        return $this->hasOne(SisrhPessoa::class, ['id_pessoa' => 'id_pessoa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSetor()
    {
        return $this->hasOne(SisrhSetor::class, ['id_setor' => 'id_setor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisapeProjetoIntegrantes()
    {
        return $this->hasMany(SisapeProjetoIntegrante::class, ['id_projeto' => 'id_projeto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisapeRelatorios()
    {
        return $this->hasMany(SisapeRelatorio::class, ['id_projeto' => 'id_projeto']);
    }
    
    /**
     * @return string
     */
    public function getTipoProjeto()
    {
        if($this->tipo_projeto === null)
            return null;
        return self::TIPO_PROJETO[$this->tipo_projeto];
    }

    /**
     * @return string
     */
    public function getTipoExtensao()
    {
        if($this->tipo_extensao === null)
            return null;
        return self::TIPO_EXTENSAO[$this->tipo_extensao];
    }

    /**
     * @return string
     */
    public function getTipoSessaoCongregacao()
    {
        if($this->tipo_sessao_congregacao === null)
            return null;
        return self::TIPO_SESSAO_CONGREGACAO[$this->tipo_sessao_congregacao];
    }

    public function getSessaoCongregacao()
    {
        if($this->sessao_congregacao === null || $this->tipo_sessao_congregacao === null)
            return null;
        return $this->sessao_congregacao.'ª Sessão '.$this->getTipoSessaoCongregacao();
    }

    /**
     * @return string
     */
    public function getSituacaoString()
    {
        if($this->situacao === null)
            $this->situacao = 0;
        if($this->pendenteRelatorio())
            return "Relatório Pendente";
        return $this::SITUACAO[$this->situacao];
    }

    public function getNumero()
    {
        return 10000 + $this->id_projeto;
    }

    public function isEditable()
    {
        return $this->situacao == 0 || $this->situacao == 16;
        //return $this->situacao == 0 || $this->situacao == 1 || $this->situacao == 4 || $this->situacao == 9 || $this->situacao == 15;
    }

    public function atualizarSituacao($situacao = false, $parecerista = false)
    {
        if(!$situacao)
            switch($this->situacao){
                case 12:
                    return false;
                case 2:
                case 7:
                case 13:
                break;
                case 3:
                case 8:
                    $this->situacao += 3;
                break;
                default:
                    $this->situacao++;
            }
            $pessoas = array();
            $destinatarios = array();
            switch($this->situacao){
                case 1:
                case 3:
                    $pessoas[] = $this->setor->getSisrhSetorPessoas()->where(['funcao' => 0])->one()->pessoa;
                break;
                case 2:
                case 5:
                case 7:
                case 10:
                    if($parecerista)
                        $pessoas[] = SisrhPessoa::findOne($parecerista);
                break;
                case 4:
                case 9:
                case 11:
                case 12:
                    $pessoas = [$this->pessoa];
                break;
                case 6:
                case 8:
                case 13:
                    $destinatarios[] = 'copexims@ufba.br';
                break;
            }
            foreach($pessoas as $pessoa){
                if($pessoa->id_user)
                    $destinatarios[] = "{$pessoa->user->username}@ufba.br";
                if(count($pessoa->emails) > 0)
                    $destinatarios[] = $pessoa->emails[0];
            }
            if($this->save()){
                if(count($destinatarios) > 0)
                    Yii::$app->mailer->compose('sisape_mensagens_projeto',['projeto' => $this]) // a view rendering result becomes the message body here
                        ->setFrom('copexims@ufba.br')
                        ->setTo($destinatarios)
                        ->setSubject('SISAPE')
                        ->send();
                return true;
            }
            return false;
    }

    /**
     * o método a seguir verifica se o projeto atual possui relatórios pendentes
     */
    public function pendenteRelatorio()
    {
        //Projetos de pesquisa e extensão eventual devem entregar relatório no prazo de 6 meses a partir da data de finalização
        if($this->tipo_projeto == self::PESQUISA || ($this->tipo_projeto == self::EXTENSAO && $this->tipo_extensao == 1))
        {
            if($this->situacao == 12 && strtotime("$this->data_fim + 6 months") < time())
                return true;
        }
        else if($this->tipo_projeto == self::EXTENSAO){
            //Projetos de extensão permanente devem entregar relatórios a cada 6 meses ou 1 ano, conforme o tipo de cada um.
            //Os comandos a seguir verificam justamente essa situação
            $periodo = $this->tipo_extensao == 2 ? '6 months' : '1 year';

            if($this->situacao == 12 && strtotime("$this->data_inicio + $periodo") < time())
                return true;
            if($this->situacao == 14){
                $relatorio = SisapeRelatorio::find()->select(['data_relatorio','situacao_projeto'])->where([
                    'id_projeto' => $this->id_projeto])->orderBy('data_relatorio DESC')->one();
                if(strtotime("$relatorio->data_relatorio + $periodo") < time())
                    return ($relatorio->situacao_projeto != 1 && $relatorio->situacao_projeto != 4);
            }
        }
        return false;
    }

    /**
     * O método a seguir verifica se a pessoa possui projetos pendentes de relatório.
     */

    public static function existePendencia($id_pessoa)
    {
        $projetos = self::find()->select('id_projeto,tipo_projeto,tipo_extensao,data_inicio,data_fim,situacao')->where(['id_pessoa' => $id_pessoa])->all();
        foreach($projetos as $projeto)
        {
            //As linhas a seguir registram somente pendências do primeiro relatório
            //fica no aguardo da COPEX para registrar pendências de relatórios periódicos
            //caso aprovem, substituir o código a seguir para usar a função pendenteRelatorio()
            $data = $projeto->data_fim != null ? $projeto->data_fim : $projeto->data_homologacao_congregacao;
            if($projeto->situacao == 12 && strtotime("$data + 6 months") < time())
                    return true;
        }
        return false;
    }
}
