<?php

namespace app\modules\sisliga\models;

use Yii;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisrh\models\SisrhSetor;

/**
 * This is the model class for table "sisliga_liga_academica".
 *
 * @property int $id_liga_academica
 * @property int $id_pessoa
 * @property int $id_setor
 * @property string $nome
 * @property string $resumo
 * @property string $area_conhecimento
 * @property string $local_atuacao
 * @property string $arquivo_solicitacao
 * @property string $arquivo_regimento
 * @property string $data_aprovacao_comissao
 * @property string $data_homologacao_congregacao
 * @property int $sessao_congregacao
 * @property int $tipo_sessao_congregacao
 * @property int $situacao
 *
 * @property SisrhPessoa $pessoa
 * @property SisrhSetor $setor
 * @property SisligaLigaIntegrante[] $sisligaLigaIntegrantes
 * @property SisligaParecer[] $sisligaParecers
 * @property SisligaRelatorio[] $sisligaRelatorios
 */
class SisligaLigaAcademica extends \yii\db\ActiveRecord
{
    //Os dois atributos a seguir servem para armazenar os arquivos subidos
    public $solicitacao;
    public $regimento;

    public const SITUACAO = [
        0 => 'Liga Acadêmica não submetida para aprovação',
        1 => 'Aguardando definir o parecerista',
        2 => 'Aguardando avaliação do parecerista',
        3 => 'Aprovada pelo parecerista',
        4 => 'Necessita de correções de acordo com parecerista',
        5 => 'Aguardando reavaliação do parecerista',
        6 => 'Liga aprovada pela Comissão',
        7 => 'Liga homologada pela Congregação',
        8 => 'Liga não homologada',
        9 => 'Liga homologada e relatório apresentado',
        10 => 'Liga encerrada',
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
        return 'sisliga_liga_academica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pessoa', 'id_setor', 'nome', 'resumo', 'area_conhecimento', 'local_atuacao'], 'required'],
            [['solicitacao', 'regimento'], 'required', 'on' => 'create'],
            [['id_pessoa', 'id_setor', 'sessao_congregacao', 'tipo_sessao_congregacao', 'situacao'], 'integer'],
            [['resumo', 'area_conhecimento', 'local_atuacao'], 'string'],
            [['data_aprovacao_comissao', 'data_homologacao_congregacao'], 'safe'],
            [['nome', 'arquivo_solicitacao', 'arquivo_regimento'], 'string', 'max' => 255],
            [['solicitacao'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf'],
            [['regimento'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf'],
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
            'id_liga_academica' => 'Liga Academica',
            'id_pessoa' => 'Pessoa',
            'id_setor' => 'Colegiado',
            'nome' => 'Nome da Liga Acadêmica',
            'resumo' => 'Resumo',
            'area_conhecimento' => 'Área(s) do Conhecimento',
            'local_atuacao' => 'Local(is) (interno(s) e/ou externo(s) à UFBA) de  Atuação',
            'arquivo_solicitacao' => 'Ofício de Solicitação de Cadastro',
            'arquivo_regimento' => 'Regimento da Liga Acadêmica',
            'data_aprovacao_comissao' => 'Data de Aprovação',
            'data_homologacao_congregacao' => 'Data de Homologação Congregação',
            'sessao_congregacao' => 'Nº da Sessão Congregação',
            'sessaoCongregacao' => 'Sessão da Congregação',
            'tipo_sessao_congregacao' => 'Tipo de Sessão da Congregação',
            'tipoSessaoCongregacao' => 'Tipo de Sessão da Congregação',
            'situacao' => 'Situação',
            'situacaoString' => 'Situação',
            'solicitacao' => 'Ofício de Solicitação de Cadastro',
            'regimento' => 'Regimento da Liga Acadêmica',
        ];
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
    public function getSisligaLigaIntegrantes()
    {
        return $this->hasMany(SisligaLigaIntegrante::class, ['id_liga_academica' => 'id_liga_academica']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisligaPareceres()
    {
        return $this->hasMany(SisligaParecer::class, ['id_liga_academica' => 'id_liga_academica']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisligaRelatorios()
    {
        return $this->hasMany(SisligaRelatorio::class, ['id_liga_academica' => 'id_liga_academica']);
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

    public function isEditable()
    {
        return $this->situacao == 0 || $this->situacao == 1 || $this->situacao == 4;
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
            if($this->save(false)){
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

    public function upload($tipoArquivo = 0)
    {
        if($this->isNewRecord){
            if ($this->validate()) {
                $this->arquivo_solicitacao = 'uploads/sisliga/solicitacao/'.time().'.pdf';
                $this->arquivo_regimento = str_replace('solicitacao','regimento',$this->arquivo_solicitacao);
            }
            else return false;
        }

            switch($tipoArquivo){
                case 0:
                    return $this->solicitacao->saveAs($this->arquivo_solicitacao) && $this->regimento->saveAs($this->arquivo_regimento);
                case 1:
                    return $this->solicitacao->saveAs($this->arquivo_solicitacao);
                case 2:
                    return $this->regimento->saveAs($this->arquivo_regimento);
            }
            return false;
    }

    /**
     * o método a seguir verifica se a liga possui relatórios pendentes
     */
    public function pendenteRelatorio()
    {
        if($this->situacao == 7 && strtotime("$this->data_homologacao_congregacao + 1 year") < time())
            return true;
        else if($this->situacao == 9){
            $relatorio = SisligaRelatorio::find()->select('data_relatorio')->where([
                'id_liga_academica' => $this->id_liga_academica])->orderBy('data_relatorio DESC')->one();
            if(strtotime("$relatorio->data_relatorio + 1 year") < time())
                return $relatorio->situacao_liga == 1;
        }
        return false;
    }

    /**
     * O método a seguir verifica se a pessoa possui ligas pendentes de relatório.
     */

    public static function existePendencia($id_pessoa)
    {
        $ligas = self::find()->select('id_liga_academica,data_homologacao_congregacao,situacao')->where(['id_pessoa' => $id_pessoa])->all();
        foreach($ligas as $liga)
            if($liga->pendenteRelatorio())
                return true;
        return false;
    }
}
