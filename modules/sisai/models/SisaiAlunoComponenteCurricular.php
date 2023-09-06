<?php

namespace app\modules\sisai\models;

use app\modules\siscc\models\SisccComponenteCurricular;
use app\modules\siscc\models\SisccProgramaComponenteCurricularPessoa;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisrh\models\SisrhSetor;
use Yii;

/**
 * This is the model class for table "sisai_aluno_componente_curricular".
 *
 * @property int $id_aluno_componente_curricular
 * @property int $id_avaliacao
 * @property int $id_componente_curricular
 * @property int $id_pessoa
 * @property int $concluida
 * @property int $id_setor
 *
 * @property SisccProgramaComponenteCurricularPessoa $componenteCurricular
 * @property SisaiAvaliacao $avaliacao
 * @property SisccComponenteCurricular $componenteCurricular0
 * @property SisrhSetor $setor
 * @property SisaiAlunoRespostaObjetiva[] $sisaiAlunoRespostaObjetivas
 * @property SisaiAlunoRespostaSubjetiva[] $sisaiAlunoRespostaSubjetivas
 */
class SisaiAlunoComponenteCurricular extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisai_aluno_componente_curricular';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_avaliacao', 'id_componente_curricular', 'id_pessoa', 'id_setor'], 'required'],
            [['id_avaliacao', 'id_componente_curricular', 'id_pessoa', 'concluida', 'id_setor'], 'integer'],
            [['id_avaliacao'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiAvaliacao::class, 'targetAttribute' => ['id_avaliacao' => 'id_avaliacao']],
            [['id_componente_curricular'], 'exist', 'skipOnError' => true, 'targetClass' => SisccComponenteCurricular::class, 'targetAttribute' => ['id_componente_curricular' => 'id_componente_curricular']],
            [['id_setor'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhSetor::class, 'targetAttribute' => ['id_setor' => 'id_setor']],
            [['id_pessoa'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhPessoa::class, 'targetAttribute' => ['id_pessoa' => 'id_pessoa']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_aluno_componente_curricular' => 'Id Aluno Componente Curricular',
            'id_avaliacao' => 'Id Avaliacao',
            'id_componente_curricular' => 'Componente Curricular',
            'id_pessoa' => 'Docente',
            'concluida' => 'Avaliação Concluída',
            'id_setor' => 'Colegiado',
            'componenteCurricular.codigoNome' => 'Componente Curricular',
            'setor.colegiado' => 'Colegiado',
            'pessoa.nome' => 'Docente',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAvaliacao()
    {
        return $this->hasOne(SisaiAvaliacao::class, ['id_avaliacao' => 'id_avaliacao']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComponenteCurricular()
    {
        return $this->hasOne(SisccComponenteCurricular::class, ['id_componente_curricular' => 'id_componente_curricular']);
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
    public function getPessoa()
    {
        return $this->hasOne(SisrhPessoa::class, ['id_pessoa' => 'id_pessoa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiAlunoRespostaObjetivas()
    {
        return $this->hasMany(SisaiAlunoRespostaObjetiva::class, ['id_aluno_componente_curricular' => 'id_aluno_componente_curricular']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiAlunoRespostaSubjetivas()
    {
        return $this->hasMany(SisaiAlunoRespostaSubjetiva::class, ['id_aluno_componente_curricular' => 'id_aluno_componente_curricular']);
    }
}
