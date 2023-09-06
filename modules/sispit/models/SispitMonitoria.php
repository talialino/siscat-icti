<?php

namespace app\modules\sispit\models;

use Yii;
use app\modules\siscc\models\SisccComponenteCurricular;
use app\modules\sisai\models\SisaiAluno;
use app\modules\sispit\config\LimiteCargaHoraria;

/**
 * This is the model class for table "sispit_monitoria".
 *
 * @property int $id_monitoria
 * @property int $id_plano_relatorio
 * @property int $semestre
 * @property int $id_aluno
 * @property int $id_componente_curricular
 * @property int $carga_horaria
 * @property int $pit_rit
 *
 * @property SisaiAluno $aluno
 * @property SisccComponenteCurricular $componenteCurricular
 * @property SispitPlanoRelatorio $planoRelatorio
 */
class SispitMonitoria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sispit_monitoria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plano_relatorio','semestre','id_componente_curricular'], 'required'],
            [['id_plano_relatorio', 'semestre', 'id_aluno', 'id_componente_curricular', 'pit_rit'], 'integer'],
            [['carga_horaria'],'integer','max' => LimiteCargaHoraria::LIMITES['monitoria']],
            [['id_aluno'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiAluno::className(), 'targetAttribute' => ['id_aluno' => 'id_aluno']],
            [['id_componente_curricular'], 'exist', 'skipOnError' => true, 'targetClass' => SisccComponenteCurricular::className(), 'targetAttribute' => ['id_componente_curricular' => 'id_componente_curricular']],
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
            'id_componente_curricular' => 'Componente Curricular',
            'componenteCurricular.nome' => 'Componente Curricular',
            'carga_horaria' => 'Carga Horária',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAluno()
    {
        return $this->hasOne(SisaiAluno::className(), ['id_aluno' => 'id_aluno']);
    }

    public function getDiscente()
    {
        if($this->id_aluno === null)
            return "A definir";
        return $this->aluno->nome;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComponenteCurricular()
    {
        return $this->hasOne(SisccComponenteCurricular::className(), ['id_componente_curricular' => 'id_componente_curricular']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoRelatorio()
    {
        return $this->hasOne(SispitPlanoRelatorio::className(), ['id_plano_relatorio' => 'id_plano_relatorio']);
    }
}
