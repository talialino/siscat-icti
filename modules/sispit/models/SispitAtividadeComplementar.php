<?php

namespace app\modules\sispit\models;

use Yii;
use app\modules\sispit\config\LimiteCargaHoraria;

/**
 * This is the model class for table "sispit_atividade_complementar".
 *
 * @property int $id_plano_relatorio
 * @property int $ch_graduacao_sem1_pit
 * @property int $ch_pos_sem1_pit
 * @property int $ch_graduacao_sem2_pit
 * @property int $ch_pos_sem2_pit
 * @property int $ch_graduacao_sem1_rit
 * @property int $ch_pos_sem1_rit
 * @property int $ch_graduacao_sem2_rit
 * @property int $ch_pos_sem2_rit
 * @property int $ch_orientacao_academica_sem1_pit
 * @property int $ch_orientacao_academica_sem2_pit
 * @property int $ch_orientacao_academica_sem1_rit
 * @property int $ch_orientacao_academica_sem2_rit
 *
 * @property SispitPlanoRelatorio $planoRelatorio
 */
class SispitAtividadeComplementar extends \yii\db\ActiveRecord
{
    public const SCENARIO_PIT = 'pit';
    public const SCENARIO_RIT = 'rit';
    public const SCENARIO_ATIVIDADE_PIT = 'atividade_pit';
    public const SCENARIO_ATIVIDADE_RIT = 'atividade_rit';
    public const SCENARIO_ORIENTACAO_PIT = 'orientacao_pit';
    public const SCENARIO_ORIENTACAO_RIT = 'orientacao_rit';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sispit_atividade_complementar';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_PIT] = ['ch_graduacao_sem1_pit', 'ch_pos_sem1_pit', 'ch_graduacao_sem2_pit', 'ch_pos_sem2_pit', 'ch_orientacao_academica_sem1_pit', 'ch_orientacao_academica_sem2_pit'];
        $scenarios[self::SCENARIO_RIT] = ['ch_graduacao_sem1_rit', 'ch_pos_sem1_rit', 'ch_graduacao_sem2_rit', 'ch_pos_sem2_rit', 'ch_orientacao_academica_sem1_rit', 'ch_orientacao_academica_sem2_rit'];
        $scenarios[self::SCENARIO_ATIVIDADE_PIT] = ['ch_graduacao_sem1_pit', 'ch_pos_sem1_pit', 'ch_graduacao_sem2_pit', 'ch_pos_sem2_pit'];
        $scenarios[self::SCENARIO_ATIVIDADE_RIT] = ['ch_graduacao_sem1_rit', 'ch_pos_sem1_rit', 'ch_graduacao_sem2_rit', 'ch_pos_sem2_rit'];
        $scenarios[self::SCENARIO_ORIENTACAO_PIT] = ['ch_orientacao_academica_sem1_pit', 'ch_orientacao_academica_sem2_pit'];
        $scenarios[self::SCENARIO_ORIENTACAO_RIT] = ['ch_orientacao_academica_sem1_rit', 'ch_orientacao_academica_sem2_rit'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plano_relatorio'], 'required'],
            [['id_plano_relatorio', 'ch_graduacao_sem1_pit', 'ch_pos_sem1_pit', 'ch_graduacao_sem2_pit', 'ch_pos_sem2_pit', 'ch_graduacao_sem1_rit', 'ch_pos_sem1_rit', 'ch_graduacao_sem2_rit', 'ch_pos_sem2_rit', 'ch_orientacao_academica_sem1_pit', 'ch_orientacao_academica_sem2_pit', 'ch_orientacao_academica_sem1_rit', 'ch_orientacao_academica_sem2_rit'], 'integer'],
            // [['ch_graduacao_sem1_pit'],'validateAtividadePit'],
            //  [['ch_graduacao_sem1_rit'],'validateAtividadeRit'],
            [['ch_orientacao_academica_sem1_pit'],'validateOrientacaoPit'],
            [['ch_orientacao_academica_sem1_rit'],'validateOrientacaoRit'],
            [['id_plano_relatorio'], 'unique'],
            [['id_plano_relatorio'], 'exist', 'skipOnError' => true, 'targetClass' => SispitPlanoRelatorio::className(), 'targetAttribute' => ['id_plano_relatorio' => 'id_plano_relatorio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ch_graduacao_sem1_pit' => 'CH Graduação do 1º semestre',
            'ch_pos_sem1_pit' => 'CH Pós-graduação do 1º semestre',
            'ch_graduacao_sem2_pit' => 'CH Graduação do 2º semestre',
            'ch_pos_sem2_pit' => 'CH Pós-graduação do 2º semestre',
            'ch_graduacao_sem1_rit' => 'CH Graduação do 1º semestre',
            'ch_pos_sem1_rit' => 'CH Pós-graduação do 1º semestre',
            'ch_graduacao_sem2_rit' => 'CH Graduação do 2º semestre',
            'ch_pos_sem2_rit' => 'CH Pós-graduação do 2º semestre',
            'ch_orientacao_academica_sem1_pit' => 'CH Total 1º semestre',
            'ch_orientacao_academica_sem2_pit' => 'CH Total 2º semestre',
            'ch_orientacao_academica_sem1_rit' => 'CH Total 1º semestre',
            'ch_orientacao_academica_sem2_rit' => 'CH Total 2º semestre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoRelatorio()
    {
        return $this->hasOne(SispitPlanoRelatorio::className(), ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    /**
     * Os métodos a segui validam os valores para cada um dos tipos de campos (atividade complementar de pit e rit, orientação acadêmica de pit e rit).
     * Atividade complementar não pode exceder o total da carga horária presencial.
     * Orientação acadêmica tem o limite definido na classe LimiteCargaHoraria e a quantidade de orientações acadêmicas que o professor realizou
     */

    public function validateAtividadePit()
    {
        //soma todas as cargas horárias de componentes, agrupados por semestre e nível de graduação
        $totais = Yii::$app->db->createCommand("SELECT semestre, nivel_graduacao, SUM(COALESCE(ch_teorica,0) + COALESCE(ch_pratica,0) + COALESCE(ch_estagio,0))
            AS total FROM sispit_ensino_componente WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = 0 GROUP BY semestre, nivel_graduacao")->queryAll();
        $limitesAtv = [0,0,0,0]; //Limites para cada um dos valores de atividade complementar
        foreach($totais as $valor){
            $limitesAtv[($valor['semestre'] == 1 ? 0 : 2) + $valor['nivel_graduacao']] = $valor['total'];
        }
        if($this->ch_graduacao_sem1_pit > $limitesAtv[0])
            $this->addError('ch_graduacao_sem1_pit', "Esse valor não pode ser superior a CH presencial ({$limitesAtv[0]})");
        if($this->ch_pos_sem1_pit > $limitesAtv[1])
            $this->addError('ch_pos_sem1_pit', "Esse valor não pode ser superior a CH presencial ({$limitesAtv[1]})");
        if($this->ch_graduacao_sem2_pit > $limitesAtv[2])
            $this->addError('ch_graduacao_sem2_pit', "Esse valor não pode ser superior a CH presencial ({$limitesAtv[2]})");
        if($this->ch_pos_sem2_pit > $limitesAtv[3])
            $this->addError('ch_pos_sem2_pit', "Esse valor não pode ser superior a CH presencial ({$limitesAtv[3]})");
    }

    public function validateAtividadeRit()
    {
        //soma todas as cargas horárias de componentes, agrupados por semestre e nível de graduação
        $totais = Yii::$app->db->createCommand("SELECT semestre, nivel_graduacao, SUM(COALESCE(ch_teorica,0) + COALESCE(ch_pratica,0) + COALESCE(ch_estagio,0))
            AS total FROM sispit_ensino_componente WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = 1 GROUP BY semestre, nivel_graduacao")->queryAll();
        $limitesAtv = [0,0,0,0]; //Limites para cada um dos valores de atividade complementar
        foreach($totais as $valor){
            $limitesAtv[($valor['semestre'] == 1 ? 0 : 2) + $valor['nivel_graduacao']] = $valor['total'];
        }
        if($this->ch_graduacao_sem1_rit > $limitesAtv[0])
            $this->addError('ch_graduacao_sem1_rit', "Esse valor não pode ser superior a CH presencial ({$limitesAtv[0]})");
        if($this->ch_pos_sem1_rit > $limitesAtv[1])
            $this->addError('ch_pos_sem1_rit', "Esse valor não pode ser superior a CH presencial ({$limitesAtv[1]})");
        if($this->ch_graduacao_sem2_rit > $limitesAtv[2])
            $this->addError('ch_graduacao_sem2_rit', "Esse valor não pode ser superior a CH presencial ({$limitesAtv[2]})");
        if($this->ch_pos_sem2_rit > $limitesAtv[3])
            $this->addError('ch_pos_sem2_rit', "Esse valor não pode ser superior a CH presencial ({$limitesAtv[3]})");
    }

    public function validateOrientacaoPit()
    {
        $count = Yii::$app->db->createCommand("SELECT semestre, COUNT(id_orientacao_academica) AS total
                FROM sispit_orientacao_academica WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = 0 GROUP BY semestre")->queryAll();
            
        $limitesOri = [0,0];
        $max = LimiteCargaHoraria::LIMITES['orientacao_academica']['max'];
        $qAlunos = LimiteCargaHoraria::LIMITES['orientacao_academica']['qAlunos'];

        foreach($count as $val){
            $limitesOri[$val['semestre'] -1] = (intdiv($val['total'],$qAlunos) + ($val['total'] % $qAlunos > 0 ? 1 : 0)) * $max;
        }

        if($this->ch_orientacao_academica_sem1_pit > $limitesOri[0])
            $this->addError('ch_orientacao_academica_sem1_pit', "Esse valor não pode ser superior a {$max}h/$qAlunos alunos");
        if($this->ch_orientacao_academica_sem2_pit > $limitesOri[1])
            $this->addError('ch_orientacao_academica_sem2_pit', "Esse valor não pode ser superior a {$max}h/$qAlunos alunos");
    }

    public function validateOrientacaoRit()
    {
        $count = Yii::$app->db->createCommand("SELECT semestre, COUNT(id_orientacao_academica) AS total
                FROM sispit_orientacao_academica WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = 1 GROUP BY semestre")->queryAll();
            
        $limitesOri = [0,0];
        $max = LimiteCargaHoraria::LIMITES['orientacao_academica']['max'];
        $qAlunos = LimiteCargaHoraria::LIMITES['orientacao_academica']['qAlunos'];

        foreach($count as $val){
            $limitesOri[$val['semestre'] -1] = (intdiv($val['total'],$qAlunos) + ($val['total'] % $qAlunos > 0 ? 1 : 0)) * $max;
        }

        if($this->ch_orientacao_academica_sem1_rit > $limitesOri[0])
            $this->addError('ch_orientacao_academica_sem1_rit', "Esse valor não pode ser superior a {$max}h/$qAlunos alunos");
        if($this->ch_orientacao_academica_sem2_rit > $limitesOri[1])
            $this->addError('ch_orientacao_academica_sem2_rit', "Esse valor não pode ser superior a {$max}h/$qAlunos alunos");
    }
}
