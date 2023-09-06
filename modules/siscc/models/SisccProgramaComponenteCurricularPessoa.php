<?php

namespace app\modules\siscc\models;

use Yii;
use app\modules\sisrh\models\SisrhPessoa;

/**
 * This is the model class for table "siscc_programa_componente_curricular_pessoa".
 *
 * @property int $id_programa_componente_curricular
 * @property int $id_pessoa
 *
 * @property SisccProgramaComponenteCurricular $programaComponenteCurricular
 * @property SisrhPessoa $pessoa
 */
class SisccProgramaComponenteCurricularPessoa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siscc_programa_componente_curricular_pessoa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_programa_componente_curricular', 'id_pessoa'], 'required'],
            [['id_programa_componente_curricular', 'id_pessoa'], 'integer'],
            [['id_programa_componente_curricular', 'id_pessoa'], 'unique', 'targetAttribute' => ['id_programa_componente_curricular', 'id_pessoa']],
            [['id_programa_componente_curricular'], 'exist', 'skipOnError' => true, 'targetClass' => SisccProgramaComponenteCurricular::class, 'targetAttribute' => ['id_programa_componente_curricular' => 'id_programa_componente_curricular']],
            [['id_pessoa'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhPessoa::class, 'targetAttribute' => ['id_pessoa' => 'id_pessoa']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_programa_componente_curricular' => 'Programa de Componente Curricular',
            'id_pessoa' => 'Docente',
            'semestre.string' => 'Semestre',
            'programaComponenteCurricular.colegiado' => 'Colegiado',
            'programaComponenteCurricular.componenteCurricular.nome' => 'Componente Curricular',
            'programaComponenteCurricular.situacaoString' => 'Situação',
            'componentePessoa' => 'Componente Curricular/Docente',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramaComponenteCurricular()
    {
        return $this->hasOne(SisccProgramaComponenteCurricular::class, ['id_programa_componente_curricular' => 'id_programa_componente_curricular']);
    }

    public function getSemestre()
    {
        return $this->hasOne(SisccSemestre::class, ['id_semestre' => 'id_semestre'])->via('programaComponenteCurricular');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoa()
    {
        return $this->hasOne(SisrhPessoa::class, ['id_pessoa' => 'id_pessoa']);
    }

    public function getComponentePessoa()
    {
        return "{$this->programaComponenteCurricular->componente} - {$this->pessoa->nome}";
    }

    public function getChavesPrimarias()
    {
        return "$this->id_programa_componente_curricular - $this->id_pessoa";
    }
}
