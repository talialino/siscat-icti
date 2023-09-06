<?php

namespace app\modules\sispit\models;

use Yii;
use app\modules\sisai\models\SisaiAluno;

/**
 * This is the model class for table "sispit_orientacao_academica".
 *
 * @property int $id_orientacao_academica
 * @property int $id_plano_relatorio
 * @property int $semestre
 * @property int $id_aluno
 * @property int $pit_rit
 *
 * @property SisaiAluno $aluno
 * @property SispitPlanoRelatorio $planoRelatorio
 */
class SispitOrientacaoAcademica extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sispit_orientacao_academica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plano_relatorio', 'semestre','id_aluno'], 'required'],
            [['id_plano_relatorio', 'semestre', 'id_aluno', 'pit_rit'], 'integer'],
            [['id_aluno'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiAluno::className(), 'targetAttribute' => ['id_aluno' => 'id_aluno']],
            [['id_plano_relatorio'], 'exist', 'skipOnError' => true, 'targetClass' => SispitPlanoRelatorio::className(), 'targetAttribute' => ['id_plano_relatorio' => 'id_plano_relatorio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'semestre' => 'Semestre',
            'id_aluno' => 'Discente',
            'aluno.nome' => 'Discente',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAluno()
    {
        return $this->hasOne(SisaiAluno::className(), ['id_aluno' => 'id_aluno']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoRelatorio()
    {
        return $this->hasOne(SispitPlanoRelatorio::className(), ['id_plano_relatorio' => 'id_plano_relatorio']);
    }
}
