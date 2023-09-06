<?php

namespace app\modules\sisape\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisape\models\SisapeAtividade;

/**
 * SisapeAtividadeSearch represents the model behind the search form of `app\modules\sisape\models\SisapeAtividade`.
 */
class SisapeAtividadeSearch extends SisapeAtividade
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_atividade', 'id_projeto', 'concluida'], 'integer'],
            [['data_inicio', 'data_fim', 'descricao_atividade'], 'safe'],
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
        $query = SisapeAtividade::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_atividade' => $this->id_atividade,
            'id_projeto' => $this->id_projeto,
            'data_inicio' => $this->data_inicio,
            'data_fim' => $this->data_fim,
            'concluida' => $this->concluida,
        ]);

        $query->andFilterWhere(['like', 'descricao_atividade', $this->descricao_atividade]);

        return $dataProvider;
    }
}
