<?php

namespace app\modules\sisape\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisape\models\SisapeFinanciamento;

/**
 * SisapeFinanciamentoSearch represents the model behind the search form of `app\modules\sisape\models\SisapeFinanciamento`.
 */
class SisapeFinanciamentoSearch extends SisapeFinanciamento
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_financiamento', 'id_projeto'], 'integer'],
            [['origem'], 'safe'],
            [['valor'], 'number'],
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
        $query = SisapeFinanciamento::find();

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
            'id_financiamento' => $this->id_financiamento,
            'id_projeto' => $this->id_projeto,
            'valor' => $this->valor,
        ]);

        $query->andFilterWhere(['like', 'origem', $this->origem]);

        return $dataProvider;
    }
}
