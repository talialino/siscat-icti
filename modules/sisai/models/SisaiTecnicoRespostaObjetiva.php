<?php

namespace app\modules\sisai\models;

use Yii;

/**
 * This is the model class for table "sisai_tecnico_resposta_objetiva".
 *
 * @property int $id_tecnico_resposta_objetiva
 * @property int $resposta
 * @property int $id_pergunta
 * @property int $id_avaliacao
 *
 * @property SisaiAvaliacao $avaliacao
 * @property SisaiPergunta $pergunta
 */
class SisaiTecnicoRespostaObjetiva extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisai_tecnico_resposta_objetiva';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resposta', 'id_pergunta', 'id_avaliacao'], 'required'],
            [['resposta', 'id_pergunta', 'id_avaliacao'], 'integer'],
            [['id_avaliacao'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiAvaliacao::class, 'targetAttribute' => ['id_avaliacao' => 'id_avaliacao']],
            [['id_pergunta'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiPergunta::class, 'targetAttribute' => ['id_pergunta' => 'id_pergunta']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tecnico_resposta_objetiva' => 'Id Tecnico Resposta Objetiva',
            'resposta' => 'Resposta',
            'id_pergunta' => 'Id Pergunta',
            'id_avaliacao' => 'Id Avaliacao',
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
}
