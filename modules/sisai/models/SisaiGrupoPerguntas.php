<?php

namespace app\modules\sisai\models;

use Yii;

/**
 * This is the model class for table "sisai_grupo_perguntas".
 *
 * @property int $id_grupo_perguntas
 * @property string $titulo
 * @property int $id_questionario
 *
 * @property SisaiQuestionario $questionario
 * @property SisaiPergunta[] $sisaiPerguntas
 */
class SisaiGrupoPerguntas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisai_grupo_perguntas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_questionario'], 'required'],
            [['id_questionario'], 'integer'],
            [['titulo'], 'string', 'max' => 255],
            [['id_questionario'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiQuestionario::class, 'targetAttribute' => ['id_questionario' => 'id_questionario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_grupo_perguntas' => 'Id Grupo Perguntas',
            'titulo' => 'Titulo',
            'id_questionario' => 'Id Questionario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionario()
    {
        return $this->hasOne(SisaiQuestionario::class, ['id_questionario' => 'id_questionario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiPerguntas()
    {
        return $this->hasMany(SisaiPergunta::class, ['id_grupo_perguntas' => 'id_grupo_perguntas'])->orderBy('id_pergunta');
    }
}
