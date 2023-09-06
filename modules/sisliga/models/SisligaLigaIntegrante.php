<?php

namespace app\modules\sisliga\models;

use Yii;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisai\models\SisaiAluno;

/**
 * This is the model class for table "sisliga_liga_integrante".
 *
 * @property int $id_liga_integrante
 * @property int $id_liga_academica
 * @property int $id_aluno
 * @property int $id_integrante_externo
 * @property int $id_pessoa
 * @property string $funcao
 * @property int $carga_horaria
 *
 * @property SisaiAluno $aluno
 * @property SisligaIntegranteExterno $integranteExterno
 * @property SisligaLigaAcademica $ligaAcademica
 * @property SisrhPessoa $pessoa
 */
class SisligaLigaIntegrante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisliga_liga_integrante';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_liga_academica'], 'required'],
            [['id_liga_integrante', 'id_liga_academica', 'id_aluno', 'id_integrante_externo', 'id_pessoa', 'carga_horaria'], 'integer'],
            [['funcao'], 'string', 'max' => 45],
            [['id_aluno'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiAluno::class, 'targetAttribute' => ['id_aluno' => 'id_aluno']],
            [['id_integrante_externo'], 'exist', 'skipOnError' => true, 'targetClass' => SisligaIntegranteExterno::class, 'targetAttribute' => ['id_integrante_externo' => 'id_integrante_externo']],
            [['id_liga_academica'], 'exist', 'skipOnError' => true, 'targetClass' => SisligaLigaAcademica::class, 'targetAttribute' => ['id_liga_academica' => 'id_liga_academica']],
            [['id_pessoa'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhPessoa::class, 'targetAttribute' => ['id_pessoa' => 'id_pessoa']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_liga_integrante' => 'Integrante',
            'id_liga_academica' => 'Liga Academica',
            'id_aluno' => 'Aluno',
            'id_integrante_externo' => 'Integrante Externo',
            'id_pessoa' => 'Pessoa',
            'funcao' => 'Função',
            'carga_horaria' => 'Carga Horária',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAluno()
    {
        return $this->hasOne(SisaiAluno::class, ['id_aluno' => 'id_aluno']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIntegranteExterno()
    {
        return $this->hasOne(SisligaIntegranteExterno::class, ['id_integrante_externo' => 'id_integrante_externo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLigaAcademica()
    {
        return $this->hasOne(SisligaLigaAcademica::class, ['id_liga_academica' => 'id_liga_academica']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoa()
    {
        return $this->hasOne(SisrhPessoa::class, ['id_pessoa' => 'id_pessoa']);
    }

    public function getNome()
    {
        $pessoa = ($this->id_pessoa != null ? $this->pessoa : 
            ($this->id_aluno != null ? $this->aluno : $this->integranteExterno));
        return $pessoa->nome;
    }

    public function getInstituicao()
    {
        return $this->id_integrante_externo != null ? $this->integranteExterno->instituicao : 'UFBA';
        
    }
}
