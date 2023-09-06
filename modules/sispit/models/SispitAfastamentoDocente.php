<?php

namespace app\modules\sispit\models;

use Yii;
use app\modules\sisrh\models\SisrhAfastamento;

/**
 * This is the model class for table "sispit_afastamento_docente".
 *
 * @property int $id_afastamento_docente
 * @property int $id_plano_relatorio
 * @property int $semestre
 * @property string $descricao
 * @property int $nivel_graduacao
 * @property int $carga_horaria
 * @property string $data_inicio
 * @property string $data_fim
 * @property int $pit_rit
 * @property bool $eh_afastamento
 *
 * @property SispitPlanoRelatorio $planoRelatorio
 * @property SisrhAfastamento $afastamento
 */
class SispitAfastamentoDocente extends \yii\db\ActiveRecord
{
    public const NIVEL_GRADUACAO = ['Mestrado','Doutorado','Pós-doutorado'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sispit_afastamento_docente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plano_relatorio','semestre', 'descricao'], 'required'],
            [['id_afastamento_docente','id_plano_relatorio', 'semestre', 'nivel_graduacao', 'carga_horaria', 'pit_rit', 'eh_afastamento'], 'integer'],
            [['descricao'], 'string', 'max' => 100],
            [['data_inicio', 'data_fim'], 'safe'],
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
            'descricao' => 'Descrição',
            'nivel_graduacao' => 'Nível',
            'nivelGraduacao' => 'Nível',
            'carga_horaria' => 'CH Média Semanal',
            'data_inicio' => 'Data Início',
            'data_fim' => 'Data Fim',
            'eh_afastamento' => 'Afastamento'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoRelatorio()
    {
        return $this->hasOne(SispitPlanoRelatorio::className(), ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    public function getNivelGraduacao()
    {
        if($this->nivel_graduacao === null)
            return null;
        
        return $this::NIVEL_GRADUACAO[$this->nivel_graduacao];
    }
}
