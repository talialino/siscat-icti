<?php

namespace app\modules\sispit\models;

use Yii;
use app\modules\siscc\models\SisccComponenteCurricular;

/**
 * This is the model class for table "sispit_ensino_componente".
 *
 * @property int $id_ensino_componente
 * @property int $id_plano_relatorio
 * @property int $id_componente_curricular
 * @property int $nivel_graduacao
 * @property int $semestre
 * @property int $ch_teorica
 * @property int $ch_pratica
 * @property int $ch_estagio
 * @property int $pit_rit
 *
 * @property SisccComponenteCurricular $componenteCurricular
 * @property SispitPlanoRelatorio $planoRelatorio
 */
class SispitEnsinoComponente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sispit_ensino_componente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plano_relatorio','id_componente_curricular','semestre'], 'required'],
            [['id_plano_relatorio', 'id_componente_curricular', 'nivel_graduacao', 'semestre', 'ch_teorica', 'ch_pratica', 'ch_estagio', 'pit_rit'], 'integer'],
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
            'id_componente_curricular' => 'Componente Curricular',
            'nivel_graduacao' => 'Nivel',
            'nivelGraduacao' => 'Nível',
            'semestre' => 'Semestre',
            'ch_teorica' => 'CH Teórica',
            'ch_pratica' => 'CH Prática',
            'ch_estagio' => 'CH Estágio',
            'componenteCurricular.nome' => 'Componente Curricular',
        ];
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

    public function getTotal()
    {
        return $this->ch_teorica + $this->ch_pratica + $this->ch_estagio;
    }

    public function getNivelGraduacao()
    {
        if($this->nivel_graduacao === null)
            return null;
        
        return $this->nivel_graduacao ? 'Pós-graduação' : 'Graduação';
    }
}
