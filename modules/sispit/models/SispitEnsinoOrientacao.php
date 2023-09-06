<?php

namespace app\modules\sispit\models;

use Yii;
use app\modules\sisai\models\SisaiAluno;
use app\modules\sispit\config\LimiteCargaHoraria;

/**
 * This is the model class for table "sispit_ensino_orientacao".
 *
 * @property int $id_ens_orientacao
 * @property int $id_plano_relatorio
 * @property int $semestre
 * @property int $id_aluno
 * @property string $projeto
 * @property int $tipo_orientacao
 * @property int $carga_horaria
 * @property int $pit_rit
 *
 * @property SisaiAluno $aluno
 * @property SispitPlanoRelatorio $planoRelatorio
 */
class SispitEnsinoOrientacao extends \yii\db\ActiveRecord
{
    //Alguns itens foram removidos depois de um período sendo utilizados, por isso a numeração não está crescente.
    //Para recuperar os valores, usar tipoOrientacao, visto que o método getTipoOrientacao recupera os itens removidos.
    public const TIPO_ORIENTACAO =[
         8 => 'PERMANECER – iniciação à pesquisa / extensão / ensino / aprendizagens profissionais',
         9 => 'SANKOFA - iniciação à pesquisa / extensão / ensino / aprendizagens profissionais',
        10 => 'PIBIC – iniciação à pesquisa',
        11 => 'PIBITI – iniciação à pesquisa',
        12 => 'PIBIEX – iniciação à extensão',
         3 => 'Trabalho de Conclusão de Curso',
         4 => 'Estágio Curricular',
         5 => 'Dissertação',
         6 => 'Tese',
         7 => 'Orientação de outra natureza',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sispit_ensino_orientacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plano_relatorio', 'tipo_orientacao', 'semestre','carga_horaria'], 'required'],
            [['id_plano_relatorio', 'semestre', 'id_aluno', 'tipo_orientacao', 'carga_horaria', 'pit_rit'], 'integer'],
            [['carga_horaria'],'validateCargaHoraria'],
            [['projeto'], 'string'],
            [['id_aluno'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiAluno::class, 'targetAttribute' => ['id_aluno' => 'id_aluno']],
            [['id_plano_relatorio'], 'exist', 'skipOnError' => true, 'targetClass' => SispitPlanoRelatorio::class, 'targetAttribute' => ['id_plano_relatorio' => 'id_plano_relatorio']],
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
            'projeto' => 'Projeto',
            'tipo_orientacao' => 'Tipo de Orientação',
            'tipoOrientacao' => 'Tipo de Orientação',
            'carga_horaria' => 'Carga Horária',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAluno()
    {
        return $this->hasOne(SisaiAluno::class, ['id_aluno' => 'id_aluno']);
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
    public function getPlanoRelatorio()
    {
        return $this->hasOne(SispitPlanoRelatorio::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    public function getTipoOrientacao()
    {
        if($this->tipo_orientacao === null)
            return null;

        $itensRemovidos = [0 => 'Iniciação à Extensão', 1 => 'Iniciação à Pesquisa', 2 => 'Bolsista Permanecer',];
        switch($this->tipo_orientacao){
            case 0:case 1: case 2:
                return $itensRemovidos[$this->tipo_orientacao];
            default:
                return $this::TIPO_ORIENTACAO[$this->tipo_orientacao];
        }
    }

    public function validateCargaHoraria()
    {
        if($this->tipo_orientacao !== null){
            $limiteCH = LimiteCargaHoraria::LIMITES['ensino_orientacao'][$this->tipo_orientacao];
            if($this->carga_horaria > $limiteCH)
            {
                $this->addError('carga_horaria', "Carga Horária não pode ser maior que $limiteCH.");
            }
        }
    }
}
