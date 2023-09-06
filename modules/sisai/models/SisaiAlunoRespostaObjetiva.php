<?php

namespace app\modules\sisai\models;

use Yii;

/**
 * This is the model class for table "sisai_aluno_resposta_objetiva".
 *
 * @property int $id_aluno_resposta_objetiva
 * @property int $resposta
 * @property int $id_pergunta
 * @property int $id_avaliacao
 * @property int $id_aluno_componente_curricular
 *
 * @property SisaiAvaliacao $avaliacao
 * @property SisaiAlunoComponenteCurricular $alunoComponenteCurricular
 * @property SisaiPergunta $pergunta
 */
class SisaiAlunoRespostaObjetiva extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisai_aluno_resposta_objetiva';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resposta', 'id_pergunta', 'id_avaliacao'], 'required'],
            [['resposta', 'id_pergunta', 'id_avaliacao', 'id_aluno_componente_curricular'], 'integer'],
            [['id_avaliacao'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiAvaliacao::class, 'targetAttribute' => ['id_avaliacao' => 'id_avaliacao']],
            [['id_aluno_componente_curricular'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiAlunoComponenteCurricular::class, 'targetAttribute' => ['id_aluno_componente_curricular' => 'id_aluno_componente_curricular']],
            [['id_pergunta'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiPergunta::class, 'targetAttribute' => ['id_pergunta' => 'id_pergunta']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_aluno_resposta_objetiva' => 'Id Aluno Resposta Objetiva',
            'resposta' => 'Resposta',
            'id_pergunta' => 'Id Pergunta',
            'id_avaliacao' => 'Id Avaliacao',
            'id_aluno_componente_curricular' => 'Id Aluno Componente Curricular',
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
    public function getAlunoComponenteCurricular()
    {
        return $this->hasOne(SisaiAlunoComponenteCurricular::class, ['id_aluno_componente_curricular' => 'id_aluno_componente_curricular']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPergunta()
    {
        return $this->hasOne(SisaiPergunta::class, ['id_pergunta' => 'id_pergunta']);
    }
}
