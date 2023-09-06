<?php

namespace app\modules\sisape\models;

use Yii;
use app\modules\sisrh\models\SisrhSetor;
use app\modules\sisrh\models\SisrhPessoa;

/**
 * This is the model class for table "sisape_relatorio".
 *
 * @property int $id_relatorio
 * @property int $id_projeto
 * @property int $situacao_projeto
 * @property string $justificativa
 * @property int $alunos_orientados
 * @property int $resumos_publicados
 * @property int $artigos_publicados
 * @property int $artigos_aceitos
 * @property int $relatorio_agencia
 * @property int $deposito_patente
 * @property string $outros_indicadores
 * @property string $consideracoes_finais
 * @property string $data_relatorio
 * @property string $data_aprovacao_nucleo
 * @property string $data_aprovacao_copex
 * @property string $data_homologacao_congregacao
 * @property int $sessao_congregacao
 * @property int $tipo_sessao_congregacao
 * @property int $situacao
 *
 * @property SisapeParecer[] $sisapeParecers
 * @property SisapeProjeto $projeto
 */
class SisapeRelatorio extends \yii\db\ActiveRecord
{

    public const SITUACAO = [
        //1 => 'Relatório preenchido, aguardando o núcleo definir o parecerista',
        //2 => 'Relatório aguardando avaliação do parecerista do núcleo',
        //3 => 'Relatório aprovado pelo parecerista do núcleo',
        //4 => 'Relatório necessita de correções de acordo com parecerista do núcleo',
        //5 => 'Relatório corrigido e aguardando reavaliação do parecerista do núcleo',
        //6 => 'Relatório aprovado pelo núcleo, aguardando a COPEX definir parecerista',
        //7 => 'Relatório aguardando avaliação do parecerista da COPEX',
        //8 => 'Relatório aprovado pelo parecerista da COPEX',
        //9 => 'Relatório necessita de correções de acordo com parecerista da COPEX',
        //10 => 'Relatório corrigido e aguardando reavaliação do parecerista da COPEX',
        13 => 'Relatório preenchido, aguardando aprovação da COPEX',
        11 => 'Relatório aprovado pela COPEX',
        12 => 'Relatório homologado pela Congregação',
        //13 => 'Relatório importado aguardando COPEX definir situação',
    ];

    public const SITUACAO_PROJETO = [
        1 => 'Não executado',
        2 => 'Paralisado',
        3 => 'Em andamento',
        4 => 'Executado',
    ];

    public const TIPO_SESSAO_CONGREGACAO = [
        1 => 'Ordinária',
        2 => 'Extraordinária',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisape_relatorio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_projeto', 'situacao', 'situacao_projeto'], 'required'],
            [['id_projeto', 'situacao_projeto', 'alunos_orientados', 'resumos_publicados', 'artigos_publicados', 'artigos_aceitos', 'relatorio_agencia', 'deposito_patente', 'sessao_congregacao', 'tipo_sessao_congregacao', 'situacao'], 'integer'],
            [['outros_indicadores','justificativa', 'consideracoes_finais'], 'string'],
            [['data_relatorio', 'data_aprovacao_nucleo', 'data_aprovacao_copex', 'data_homologacao_congregacao'], 'safe'],
            [['id_projeto'], 'exist', 'skipOnError' => true, 'targetClass' => SisapeProjeto::class, 'targetAttribute' => ['id_projeto' => 'id_projeto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_relatorio' => 'Relatório',
            'id_projeto' => 'Projeto',
            'projetoTitulo' => 'Título do Projeto',
            'situacao_projeto' => 'Situação do Projeto',
            'situacaoProjeto' => 'Situação do Projeto',
            'justificativa' => 'Justificativa',
            'alunos_orientados' => 'Número de alunos orientados',
            'resumos_publicados' => 'Número de resumos publicados',
            'artigos_publicados' => 'Número de artigos publicados',
            'artigos_aceitos' => 'Número de artigos aceitos para publicação',
            'relatorio_agencia' => 'Relatório técnico apresentado à agência de fomento',
            'deposito_patente' => 'Depósito de Patente',
            'outros_indicadores' => 'Outros Indicadores',
            'consideracoes_finais' => 'Considerações Finais',
            'data_relatorio' => 'Data do Relatório',
            'data_aprovacao_nucleo' => 'Data de Aprovação - Núcleo',
            'data_aprovacao_copex' => 'Data de Aprovação - COPEX',
            'data_homologacao_congregacao' => 'Data de Homologação Congregação',
            'sessao_congregacao' => 'Nº da Sessão Congregação',
            'tipo_sessao_congregacao' => 'Tipo de Sessão da Congregação',
            'tipoSessaoCongregacao' => 'Tipo de Sessão da Congregação',
            'situacao' => 'Situação',
            'situacaoString' => 'Situação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisapePareceres()
    {
        return $this->hasMany(SisapeParecer::class, ['id_relatorio' => 'id_relatorio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjeto()
    {
        return $this->hasOne(SisapeProjeto::class, ['id_projeto' => 'id_projeto']);
    }

    /**
     * @return string
     */
    public function getProjetoTitulo()
    {
        return $this->projeto->titulo;
    }

    /**
     * Os dois métodos a seguir são utilizados por SisapeParecer e/ou para mensagens de e-mail
     */
    public function getTitulo()
    {
        return 'Relatório do projeto: '.$this->getProjetoTitulo();
    }

    public function getTipoProjeto()
    {
        return $this->projeto->tipoProjeto;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoa()
    {
        return $this->hasOne(SisrhPessoa::class, ['id_pessoa' => 'id_pessoa'])->via('projeto');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSetor()
    {
        return $this->hasOne(SisrhSetor::class, ['id_setor' => 'id_setor'])->via('projeto');
    }

    /**
     * @return string
     */
    public function getSituacaoString()
    {
        return $this::SITUACAO[$this->situacao];
    }

    public function getSituacaoProjeto()
    {
        return $this::SITUACAO_PROJETO[$this->situacao_projeto];
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

    public function isEditable()
    {
        return $this->situacao == 13;
        //return $this->situacao == 0 || $this->situacao == 1 || $this->situacao == 4 || $this->situacao == 9;
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
}