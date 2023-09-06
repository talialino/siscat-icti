<?php

namespace app\modules\sisrh\models;

use app\modules\sisai\models\SisaiAluno;
use app\modules\siscc\models\SisccProgramaComponenteCurricular;
use Yii;

/**
 * This is the model class for table "sisrh_setor".
 *
 * @property int $id_setor
 * @property string $nome
 * @property int $id_setor_responsavel
 * @property int $codigo
 * @property string $sigla
 * @property string $email
 * @property string $ramais
 * @property int $eh_colegiado
 * @property int $eh_nucleo_academico
 * @property string $observacao
 *
 * @property SisaiAluno[] $sisaiAlunos
 * @property SisaiAvaliacaoProfessorTurmaComponente[] $sisaiAvaliacaoProfessorTurmaComponentes
 * @property SisccProgramaComponenteCurricular[] $sisccProgramaComponenteCurriculares
 * @property SisrhSetor $setorResponsavel
 * @property SisrhSetor[] $sisrhSetores
 * @property SisrhSetorPessoa[] $sisrhSetorPessoas
 * @property SisrhPessoa[] $pessoas
 * @property SisrhHistoricoFuncional[] $sisrhHistoricoFuncionais
 */
class SisrhSetor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisrh_setor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['id_setor_responsavel', 'codigo', 'eh_colegiado', 'eh_nucleo_academico'], 'integer'],
            [['email', 'ramais', 'observacao'], 'string'],
            [['nome'], 'string', 'max' => 100],
            [['sigla'], 'string', 'max' => 20],
            [['id_setor_responsavel'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhSetor::class, 'targetAttribute' => ['id_setor_responsavel' => 'id_setor']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_setor' => 'Setor',
            'nome' => 'Nome',
            'id_setor_responsavel' => 'Setor Responsável',
            'setorResponsavel.nome' =>'Setor Responsável',
            'codigo' => 'Código',
            'sigla' => 'Sigla',
            'email' => 'Email',
            'ramais' => 'Ramais',
            'eh_colegiado' => 'Colegiado',
            'eh_nucleo_academico' => 'Núcleo Acadêmico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiAlunos()
    {
        return $this->hasMany(SisaiAluno::class, ['id_setor' => 'id_setor']);
    }

    // /**
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getSisaiAvaliacaoProfessorTurmaComponentes()
    // {
    //     return $this->hasMany(SisaiAvaliacaoProfessorTurmaComponente::class, ['id_setor' => 'id_setor']);
    // }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisccProgramaComponenteCurriculares()
    {
        return $this->hasMany(SisccProgramaComponenteCurricular::class, ['id_setor' => 'id_setor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSetorResponsavel()
    {
        return $this->hasOne(SisrhSetor::class, ['id_setor' => 'id_setor_responsavel']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisrhSetores()
    {
        return $this->hasMany(SisrhSetor::class, ['id_setor_responsavel' => 'id_setor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisrhSetorPessoas()
    {
        return $this->hasMany(SisrhSetorPessoa::class, ['id_setor' => 'id_setor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoas()
    {
        return $this->hasMany(SisrhPessoa::class, ['id_pessoa' => 'id_pessoa'])->viaTable('sisrh_setor_pessoa', ['id_setor' => 'id_setor']);
    }

    // /* *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getSisrhHistoricoFuncionais()
    // {
    //     return $this->hasMany(SisrhHistoricoFuncional::class, ['id_setor' => 'id_setor']);
    // }

    /**
     * Caso o setor seja um colegiado, retorna seu nome reduzido
     */
    public function getColegiado()
    {
        return self::ajustarNomeColegiado($this->nome);
    }

    public static function ajustarNomeColegiado($colegiado)
    {
        $search = ['Colegiado do ','Curso de ', 'Programa de ', 'Programa Multicêntrico de '];
        $replace = ['','','',''];
        return str_replace($search,$replace,$colegiado);
    }
}
