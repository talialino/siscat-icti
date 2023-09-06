<?php

namespace app\modules\siscc\models;

use Yii;
use app\modules\sisrh\models\SisrhSetor;
use app\modules\sisrh\models\SisrhPessoa;

/**
 * This is the model class for table "siscc_programa_componente_curricular".
 *
 * @property int $id_programa_componente_curricular
 * @property int $id_componente_curricular
 * @property int $id_setor
 * @property int $id_semestre
 * @property string $objetivo_geral
 * @property string $objetivos_especificos
 * @property string $conteudo_programatico
 * @property string $data_aprovacao_colegiado
 * @property string $data_aprovacao_coordenacao
 * @property int $situacao
 *
 * @property SisccComponenteCurricular $componenteCurricular
 * @property SisrhSetor $setor
 * @property SisccSemestre $semestre
 * @property SisccProgramaComponenteCurricularBibliografia[] $sisccProgramaComponenteCurricularBibliografias
 * @property SisccBibliografia[] $bibliografias
 * @property SisccProgramaComponenteCurricularPessoa[] $sisccProgramaComponenteCurricularPessoas
 * @property SisrhPessoa[] $pessoas
 */
class SisccProgramaComponenteCurricular extends \yii\db\ActiveRecord
{
    public const SITUACAO = [
        0 => 'Programa não preenchido ou não submetido para o colegiado apreciar',
        1 => 'Programa preenchido, aguardando o colegiado definir o parecerista',
        2 => 'Programa aguardando avaliação do parecerista do colegiado',
        3 => 'Programa aprovado pelo parecerista do colegiado',
        4 => 'Programa necessita de correções de acordo com parecerista do colegiado',
        5 => 'Programa corrigido e aguardando reavaliação do parecerista do colegiado',
        6 => 'Programa aprovado pelo colegiado, aguardando a CAC definir parecerista',
        7 => 'Programa aguardando avaliação do parecerista da CAC',
        8 => 'Programa aprovado pelo parecerista da CAC',
        9 => 'Programa necessita de correções de acordo com parecerista da CAC',
        10 => 'Programa corrigido e aguardando reavaliação do parecerista da CAC',
        11 => 'Programa homologado pela CAC',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siscc_programa_componente_curricular';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_componente_curricular', 'id_setor', 'id_semestre'], 'required'],
            [['id_componente_curricular', 'id_setor', 'id_semestre', 'situacao'], 'integer'],
            [['objetivo_geral', 'objetivos_especificos', 'conteudo_programatico'], 'string'],
            [['data_aprovacao_colegiado', 'data_aprovacao_coordenacao'], 'safe'],
            [['id_componente_curricular'], 'exist', 'skipOnError' => true, 'targetClass' => SisccComponenteCurricular::class, 'targetAttribute' => ['id_componente_curricular' => 'id_componente_curricular']],
            [['id_setor'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhSetor::class, 'targetAttribute' => ['id_setor' => 'id_setor']],
            [['id_semestre'], 'exist', 'skipOnError' => true, 'targetClass' => SisccSemestre::class, 'targetAttribute' => ['id_semestre' => 'id_semestre']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_componente_curricular' => 'Componente Curricular',
            'id_setor' => 'Colegiado',
            'id_semestre' => 'Semestre',
            'semestreString' => 'Semestre',
            'objetivo_geral' => 'Objetivo Geral',
            'objetivos_especificos' => 'Objetivos Específicos',
            'conteudo_programatico' => 'Conteúdo Programático',
            'data_aprovacao_colegiado' => 'Data de Aprovação Colegiado',
            'data_aprovacao_coordenacao' => 'Data de Aprovação Coordenação Acadêmica de Ensino',
            'situacao' => 'Situação',
            'situacaoString' => 'Situação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComponenteCurricular()
    {
        return $this->hasOne(SisccComponenteCurricular::class, ['id_componente_curricular' => 'id_componente_curricular']);
    }

    public function getComponente()
    {
        return "{$this->componenteCurricular->codigo_componente} - {$this->componenteCurricular->nome}";
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSetor()
    {
        return $this->hasOne(SisrhSetor::class, ['id_setor' => 'id_setor']);
    }

    /**
     * @return string
     */
    public function getColegiado()
    {
        $colegiado = $this->setor->nome;

        return str_replace('Colegiado do ','',$colegiado);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemestre()
    {
        return $this->hasOne(SisccSemestre::class, ['id_semestre' => 'id_semestre']);
    }

    public function getSemestreString()
    {
        if($this->componenteCurricular->anual && $this->semestre->semestre < 3)
            return $this->semestre->semestre == 1 ? "{$this->semestre->ano} (Anual)" :
                $this->semestre->string.' - '.($this->semestre->ano+1).'.1';
        return $this->semestre->string;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisccProgramaComponenteCurricularBibliografias()
    {
        return $this->hasMany(SisccProgramaComponenteCurricularBibliografia::class, ['id_programa_componente_curricular' => 'id_programa_componente_curricular'])->joinWith('bibliografia as bibliografia')->orderBy('tipo_referencia ASC, nome ASC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBibliografias()
    {
        return $this->hasMany(SisccBibliografia::class, ['id_bibliografia' => 'id_bibliografia'])->viaTable('siscc_programa_componente_curricular_bibliografia', ['id_programa_componente_curricular' => 'id_programa_componente_curricular']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisccProgramaComponenteCurricularPessoas()
    {
        return $this->hasMany(SisccProgramaComponenteCurricularPessoa::class, ['id_programa_componente_curricular' => 'id_programa_componente_curricular']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoas()
    {
        return $this->hasMany(SisrhPessoa::class, ['id_pessoa' => 'id_pessoa'])->viaTable('siscc_programa_componente_curricular_pessoa', ['id_programa_componente_curricular' => 'id_programa_componente_curricular'])->orderby('nome ASC');
    }

    public function getSituacaoString()
    {
        if($this->situacao === null)
            return null;
        return $this::SITUACAO[$this->situacao];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisccPareceres()
    {
        return $this->hasMany(SisccParecer::class, ['id_programa_componente_curricular' => 'id_programa_componente_curricular'])->where("parecer IS NOT NULL AND parecer NOT LIKE ''")->orderby('data DESC, id_parecer DESC');
    }

    public function isEditable()
    {
        return $this->situacao == 0 || $this->situacao == 1 || $this->situacao == 4 || $this->situacao == 9;
    }

    public function atualizarSituacao($situacao = false, $parecerista = false)
    {
        if(!$situacao)
            switch($this->situacao){
                case 11:
                    return false;
                case 2:
                case 7:
                break;
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
                    $pessoas = $this->pessoas;
                break;
                case 6:
                case 8:
                    $destinatarios[] = 'coordenacaoacademica@ufba.br';
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
                    Yii::$app->mailer->compose('siscc_mensagens_programa',['programa' => $this]) // a view rendering result becomes the message body here
                        ->setFrom('coordenacaoacademica@ufba.br')
                        ->setTo($destinatarios)
                        ->setSubject('SISCC')
                        ->send();
                return true;
            }
            return false;
    }

    /**
     * Verifica se os dados do programa podem ser importados de outro programa.
     * Isso só está disponível para semestres regulares e caso o programa não tenha
     * nenhuma bibliografia ligada a ele
     * @return bool
     */
    public function podeImportar()
    {
        if($this->situacao > 0 || $this->semestre->semestre == 3 || $this->getSisccProgramaComponenteCurricularBibliografias()->exists())
            return false;
        return true;
    }
}
