<?php

namespace app\modules\sisape\models;

use Yii;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisai\models\SisaiAluno;

/**
 * This is the model class for table "sisape_projeto_integrante".
 *
 * @property int $id_projeto_integrante
 * @property int $id_projeto
 * @property int $id_integrante_externo
 * @property int $id_pessoa
 * @property string $funcao
 * @property int $id_aluno falta vincular id_aluno a  sisai_aluno
 * @property int $carga_horaria
 * @property string $vinculo
 *
 * @property SisapeIntegranteExterno $integranteExterno
 * @property SisapeProjeto $projeto
 * @property SisrhPessoa $pessoa
 */
class SisapeProjetoIntegrante extends \yii\db\ActiveRecord
{
    public const VINCULOS = [
        1 => 'Voluntário',
        2 => 'Bolsa PIBIC',
        3 => 'Bolsa PIBIEX',
        4 => 'Bolsa PERMANECER',
        5 => 'Outro',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisape_projeto_integrante';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_projeto'], 'required'],
            [['id_projeto', 'id_integrante_externo', 'id_pessoa', 'id_aluno', 'carga_horaria','vinculo'], 'integer'],
            [['funcao'], 'string', 'max' => 255],
            [['id_integrante_externo'], 'exist', 'skipOnError' => true, 'targetClass' => SisapeIntegranteExterno::class, 'targetAttribute' => ['id_integrante_externo' => 'id_integrante_externo']],
            [['id_projeto'], 'exist', 'skipOnError' => true, 'targetClass' => SisapeProjeto::class, 'targetAttribute' => ['id_projeto' => 'id_projeto']],
            [['id_pessoa'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhPessoa::class, 'targetAttribute' => ['id_pessoa' => 'id_pessoa']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_integrante_externo' => 'Integrante Externo',
            'id_pessoa' => 'Docente ou Técnico',
            'funcao' => 'Função no Projeto',
            'id_aluno' => 'Discente',
            'carga_horaria' => 'CH Semanal',
            'vinculo' => 'Vínculo',
            'vinculoString' => 'Vínculo',
            'projeto.titulo' => 'Projeto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIntegranteExterno()
    {
        return $this->hasOne(SisapeIntegranteExterno::class, ['id_integrante_externo' => 'id_integrante_externo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjeto()
    {
        return $this->hasOne(SisapeProjeto::class, ['id_projeto' => 'id_projeto']);
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
    public function getAluno()
    {
        return $this->hasOne(SisaiAluno::class, ['id_aluno' => 'id_aluno']);
    }

    public function getNome()
    {
        $pessoa = ($this->id_pessoa != null ? $this->pessoa : 
            ($this->id_aluno != null ? $this->aluno : $this->integranteExterno));
        return $pessoa->nome;
    }

    public function getVinculoString()
    {
        if($this->id_pessoa != null)
            return $this->pessoa->categoria->nome;
        if($this->vinculo === null)
            return null;
        return self::VINCULOS[$this->vinculo];
    }
}
