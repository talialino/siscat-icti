<?php

namespace app\modules\sispit\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sispit\models\SispitParticipacaoEvento;

/**
 * SispitParticipacaoEventoSearch represents the model behind the search form of `app\modules\sispit\models\SispitParticipacaoEvento`.
 */
class SispitParticipacaoEventoSearch extends SispitParticipacaoEvento
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_participacao_evento', 'id_plano_relatorio', 'semestre', 'tipo_evento', 'tipo_participacao_evento', 'pit_rit'], 'integer'],
            [['nome', 'local', 'data_inicio', 'data_fim'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SispitParticipacaoEvento::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_participacao_evento' => $this->id_participacao_evento,
            'id_plano_relatorio' => $this->id_plano_relatorio,
            'semestre' => $this->semestre,
            'tipo_evento' => $this->tipo_evento,
            'tipo_participacao_evento' => $this->tipo_participacao_evento,
            'data_inicio' => $this->data_inicio,
            'data_fim' => $this->data_fim,
            'pit_rit' => $this->pit_rit,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'local', $this->local]);

        $query->orderBy('semestre ASC');

        return $dataProvider;
    }
}
