<?php

namespace app\modules\siscc\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\siscc\models\SisccBibliografia;

/**
 * SisccBibliografiaSearch represents the model behind the search form of `app\modules\siscc\models\SisccBibliografia`.
 */
class SisccBibliografiaSearch extends SisccBibliografia
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_bibliografia'], 'integer'],
            [['nome'], 'safe'],
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
        $query = SisccBibliografia::find();

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
            'id_bibliografia' => $this->id_bibliografia,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome]);

        return $dataProvider;
    }
}
