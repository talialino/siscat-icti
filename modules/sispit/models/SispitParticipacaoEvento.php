<?php

namespace app\modules\sispit\models;

use Yii;

/**
 * This is the model class for table "sispit_participacao_evento".
 *
 * @property int $id_participacao_evento
 * @property int $id_plano_relatorio
 * @property string $nome
 * @property int $semestre
 * @property int $tipo_evento
 * @property int $tipo_participacao_evento
 * @property string $local
 * @property string $data_inicio
 * @property string $data_fim
 * @property int $pit_rit
 *
 * @property SispitPlanoRelatorio $planoRelatorio
 */
class SispitParticipacaoEvento extends \yii\db\ActiveRecord
{
    public const TIPO_EVENTO = ['Curso','Seminário','Palestra','Simpósio','Encontro','Congresso','Outro'];
    public const TIPO_PARTICIPACAO = ['Comunicador','Poster','Coordenador','Mini-curso','Ouvinte','Outro'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sispit_participacao_evento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plano_relatorio','semestre'], 'required'],
            [['id_plano_relatorio', 'semestre', 'tipo_evento', 'tipo_participacao_evento', 'pit_rit'], 'integer'],
            [['nome'], 'string'],
            [['data_inicio', 'data_fim'], 'safe'],
            [['local'], 'string', 'max' => 150],
            [['id_plano_relatorio'], 'exist', 'skipOnError' => true, 'targetClass' => SispitPlanoRelatorio::className(), 'targetAttribute' => ['id_plano_relatorio' => 'id_plano_relatorio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nome' => 'Nome do evento',
            'semestre' => 'Semestre',
            'tipo_evento' => 'Tipo de evento',
            'tipoEvento' => 'Tipo de evento',
            'tipo_participacao_evento' => 'Tipo de participação no evento',
            'tipoParticipacao' => 'Tipo de participação no evento',
            'local' => 'Local',
            'data_inicio' => 'Data Início',
            'data_fim' => 'Data Fim',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoRelatorio()
    {
        return $this->hasOne(SispitPlanoRelatorio::className(), ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    public function getTipoEvento()
    {
        if($this->tipo_evento === null)
            return null;
        return $this::TIPO_EVENTO[$this->tipo_evento];
    }

    public function getTipoParticipacao()
    {
        if($this->tipo_participacao_evento === null)
            return null;
        return $this::TIPO_PARTICIPACAO[$this->tipo_participacao_evento];
    }
}
