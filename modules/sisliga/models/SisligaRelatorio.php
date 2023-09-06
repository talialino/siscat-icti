<?php

namespace app\modules\sisliga\models;

use Yii;
use app\modules\sisrh\models\SisrhPessoa;

/**
 * This is the model class for table "sisliga_relatorio".
 *
 * @property int $id_relatorio
 * @property int $id_liga_academica
 * @property string $atividades
 * @property string $prestacao_contas
 * @property string $consideracoes_finais
 * @property string $data_aprovacao_comissao
 * @property string $data_homologacao_congregacao
 * @property int $sessao_congregacao
 * @property int $tipo_sessao_congregacao
 * @property string $data_relatorio
 * @property string $data_inicio
 * @property string $data_fim
 * @property int $situacao
 * @property int $situacao_liga
 *
 * @property SisligaParecer[] $sisligaParecers
 * @property SisligaLigaAcademica $ligaAcademica
 */
class SisligaRelatorio extends \yii\db\ActiveRecord
{
    public const SITUACAO = [

        1 => 'Relatório aguardando definir o parecerista',
        2 => 'Relatório aguardando avaliação do parecerista',
        3 => 'Relatório aprovado pelo parecerista',
        4 => 'Relatório necessita de correções de acordo com parecerista',
        5 => 'Relatório aguardando reavaliação do parecerista',
        6 => 'Relatório aprovado pela Comissão',
        7 => 'Relatório homologado pela Congregação',
    ];

    public const SITUACAO_LIGA = [
        1 => 'Vigente',
        2 => 'Encerrada',
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
        return 'sisliga_relatorio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_liga_academica', 'atividades', 'situacao_liga'], 'required'],
            [['id_liga_academica', 'sessao_congregacao', 'tipo_sessao_congregacao', 'situacao', 'situacao_liga'], 'integer'],
            [['atividades', 'prestacao_contas', 'consideracoes_finais'], 'string'],
            [['data_aprovacao_comissao', 'data_homologacao_congregacao', 'data_relatorio', 'data_inicio', 'data_fim'], 'safe'],
            [['id_liga_academica'], 'exist', 'skipOnError' => true, 'targetClass' => SisligaLigaAcademica::class, 'targetAttribute' => ['id_liga_academica' => 'id_liga_academica']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_relatorio' => 'Id Relatorio',
            'id_liga_academica' => 'Liga Acadêmica',
            'atividades' => 'Atividades',
            'prestacao_contas' => 'Prestação de Contas',
            'consideracoes_finais' => 'Considerações Finais',
            'data_aprovacao_comissao' => 'Data de Aprovação',
            'data_relatorio' => 'Data do Relatório',
            'data_inicio' => 'Data Início',
            'data_fim' => 'Data Fim',
            'data_homologacao_congregacao' => 'Data de Homologação Congregação',
            'sessao_congregacao' => 'Nº da Sessão Congregação',
            'sessaoCongregacao' => 'Sessão da Congregação',
            'tipo_sessao_congregacao' => 'Tipo de Sessão da Congregação',
            'tipoSessaoCongregacao' => 'Tipo de Sessão da Congregação',
            'situacao' => 'Situação',
            'situacaoString' => 'Situação',
            'situacao_liga' => 'Situação da Liga Acadêmica',
            'situacaoLigaString' => 'Situação da Liga Acadêmica',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisligaPareceres()
    {
        return $this->hasMany(SisligaParecer::class, ['id_relatorio' => 'id_relatorio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLigaAcademica()
    {
        return $this->hasOne(SisligaLigaAcademica::class, ['id_liga_academica' => 'id_liga_academica']);
    }
    /**
     * @return string
     */
    public function getLigaNome()
    {
        return $this->ligaAcademica->nome;
    }

    /**
     * O método a seguir é utilizado por SisligaParecer e/ou para mensagens de e-mail
     */
    public function getNome()
    {
        return 'Relatório da Liga Acadêmica: '.$this->getLigaNome();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoa()
    {
        return $this->hasOne(SisrhPessoa::class, ['id_pessoa' => 'id_pessoa'])->via('ligaAcademica');
    }

    /**
     * @return string
     */
    public function getSituacaoString()
    {
        return $this::SITUACAO[$this->situacao];
    }

    /**
     * @return string
     */
    public function getSituacaoLigaString()
    {
        return $this::SITUACAO_LIGA[$this->situacao_liga];
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
        return $this->situacao == 1 || $this->situacao == 4;
    }

    public function atualizarSituacao($situacao = false, $parecerista = false)
    {
        if(!$situacao)
            switch($this->situacao){
                case 7:
                    return false;
                case 2:
                break;
                case 3:
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
                    $destinatarios[] = 'copexims@ufba.br';
                break;
                case 2:
                case 5:
                    if($parecerista)
                        $pessoas[] = SisrhPessoa::findOne($parecerista);
                break;
                case 6:
                case 7:
                case 8:
                    $pessoas = [$this->pessoa];
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
                    Yii::$app->mailer->compose('sisliga_mensagens_liga_relatorio',['documento' => $this]) // a view rendering result becomes the message body here
                        ->setFrom('copexims@ufba.br')
                        ->setTo($destinatarios)
                        ->setSubject('SISAPE')
                        ->send();
                return true;
            }
            return false;
    }
}
