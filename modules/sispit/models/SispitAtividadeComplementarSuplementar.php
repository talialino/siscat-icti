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
class SispitAtividadeComplementarSuplementar extends SispitAtividadeComplementar
{

    // public function scenarios()
    // {
    //     $scenarios = parent::scenarios();
    //     $scenarios[self::SCENARIO_PIT] = ['ch_graduacao_sem1_pit', 'ch_pos_sem1_pit', 'ch_graduacao_sem2_pit', 'ch_pos_sem2_pit', 'ch_orientacao_academica_sem1_pit', 'ch_orientacao_academica_sem2_pit'];
    //     $scenarios[self::SCENARIO_RIT] = ['ch_graduacao_sem1_rit', 'ch_pos_sem1_rit', 'ch_graduacao_sem2_rit', 'ch_pos_sem2_rit', 'ch_orientacao_academica_sem1_rit', 'ch_orientacao_academica_sem2_rit'];
    //     $scenarios[self::SCENARIO_ATIVIDADE_PIT] = ['ch_graduacao_sem1_pit', 'ch_pos_sem1_pit', 'ch_graduacao_sem2_pit', 'ch_pos_sem2_pit'];
    //     $scenarios[self::SCENARIO_ATIVIDADE_RIT] = ['ch_graduacao_sem1_rit', 'ch_pos_sem1_rit', 'ch_graduacao_sem2_rit', 'ch_pos_sem2_rit'];
    //     $scenarios[self::SCENARIO_ORIENTACAO_PIT] = ['ch_orientacao_academica_sem1_pit', 'ch_orientacao_academica_sem2_pit'];
    //     $scenarios[self::SCENARIO_ORIENTACAO_RIT] = ['ch_orientacao_academica_sem1_rit', 'ch_orientacao_academica_sem2_rit'];

    //     return $scenarios;
    // }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plano_relatorio'], 'required'],
            [['id_plano_relatorio', 'ch_graduacao_sem1_pit', 'ch_pos_sem1_pit', 'ch_graduacao_sem2_pit', 'ch_pos_sem2_pit', 'ch_graduacao_sem1_rit', 'ch_pos_sem1_rit', 'ch_graduacao_sem2_rit', 'ch_pos_sem2_rit', 'ch_orientacao_academica_sem1_pit', 'ch_orientacao_academica_sem2_pit', 'ch_orientacao_academica_sem1_rit', 'ch_orientacao_academica_sem2_rit'], 'integer'],
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
            'ch_graduacao_sem1_pit' => 'CH Graduação',
            'ch_pos_sem1_pit' => 'CH Pós-graduação',
            'ch_graduacao_sem1_rit' => 'CH Graduação',
            'ch_pos_sem1_rit' => 'CH Pós-graduação',
            'ch_orientacao_academica_sem1_pit' => 'CH Total',
            'ch_orientacao_academica_sem1_rit' => 'CH Total',
        ];
    }

    /**
     * Os métodos a segui validam os valores para cada um dos tipos de campos (orientação acadêmica de pit e rit).
     * Orientação acadêmica tem o limite definido na classe LimiteCargaHoraria e a quantidade de orientações acadêmicas que o professor realizou
     */


    // public function validateOrientacaoPit()
    // {
    //     $count = Yii::$app->db->createCommand("SELECT semestre, COUNT(id_orientacao_academica) AS total
    //             FROM sispit_orientacao_academica WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = 0 GROUP BY semestre")->queryAll();
            
    //     $limitesOri = [0,0];
    //     $max = LimiteCargaHoraria::LIMITES['orientacao_academica']['max'];
    //     $qAlunos = LimiteCargaHoraria::LIMITES['orientacao_academica']['qAlunos'];

    //     foreach($count as $val){
    //         $limitesOri[$val['semestre'] -1] = (intdiv($val['total'],$qAlunos) + ($val['total'] % $qAlunos > 0 ? 1 : 0)) * $max;
    //     }

    //     if($this->ch_orientacao_academica_sem1_pit > $limitesOri[0])
    //         $this->addError('ch_orientacao_academica_sem1_pit', "Esse valor não pode ser superior a {$max}h/$qAlunos alunos");
    // }

    // public function validateOrientacaoRit()
    // {
    //     $count = Yii::$app->db->createCommand("SELECT semestre, COUNT(id_orientacao_academica) AS total
    //             FROM sispit_orientacao_academica WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = 1 GROUP BY semestre")->queryAll();
            
    //     $limitesOri = [0,0];
    //     $max = LimiteCargaHoraria::LIMITES['orientacao_academica']['max'];
    //     $qAlunos = LimiteCargaHoraria::LIMITES['orientacao_academica']['qAlunos'];

    //     foreach($count as $val){
    //         $limitesOri[$val['semestre'] -1] = (intdiv($val['total'],$qAlunos) + ($val['total'] % $qAlunos > 0 ? 1 : 0)) * $max;
    //     }

    //     if($this->ch_orientacao_academica_sem1_rit > $limitesOri[0])
    //         $this->addError('ch_orientacao_academica_sem1_rit', "Esse valor não pode ser superior a {$max}h/$qAlunos alunos");
    // }
}
