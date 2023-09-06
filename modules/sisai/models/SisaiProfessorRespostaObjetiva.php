<?php

namespace app\modules\sisai\models;

use Yii;

/**
 * This is the model class for table "sisai_professor_resposta_objetiva".
 *
 * @property int $id_professor_resposta_objetiva
 * @property int $resposta
 * @property int $id_pergunta
 * @property int $id_avaliacao
 * @property int $id_professor_componente_curricular
 *
 * @property SisaiAvaliacao $avaliacao
 * @property SisaiPergunta $pergunta
 * @property SisaiProfessorComponenteCurricular $professorComponenteCurricular
 */
class SisaiProfessorRespostaObjetiva extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisai_professor_resposta_objetiva';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resposta', 'id_pergunta', 'id_avaliacao'], 'required'],
            [['resposta', 'id_pergunta', 'id_avaliacao', 'id_professor_componente_curricular'], 'integer'],
            [['id_avaliacao'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiAvaliacao::class, 'targetAttribute' => ['id_avaliacao' => 'id_avaliacao']],
            [['id_pergunta'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiPergunta::class, 'targetAttribute' => ['id_pergunta' => 'id_pergunta']],
            [['id_professor_componente_curricular'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiProfessorComponenteCurricular::class, 'targetAttribute' => ['id_professor_componente_curricular' => 'id_professor_componente_curricular']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_professor_resposta_objetiva' => 'Id Professor Resposta Objetiva',
            'resposta' => 'Resposta',
            'id_pergunta' => 'Id Pergunta',
            'id_avaliacao' => 'Id Avaliacao',
            'id_professor_componente_curricular' => 'Id Programa Componente Curricular',
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
    public function getPergunta()
    {
        return $this->hasOne(SisaiPergunta::class, ['id_pergunta' => 'id_pergunta']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfessorComponenteCurricular()
    {
        return $this->hasOne(SisaiProfessorComponenteCurricular::class, ['id_professor_componente_curricular' => 'id_professor_componente_curricular']);
    }
}
