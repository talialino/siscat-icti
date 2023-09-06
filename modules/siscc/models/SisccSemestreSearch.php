<?php

namespace app\modules\siscc\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\siscc\models\SisccSemestre;

/**
 * SisccSemestreSearch represents the model behind the search form of `app\modules\siscc\models\SisccSemestre`.
 */
class SisccSemestreSearch extends SisccSemestre
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_semestre', 'ano', 'semestre'], 'integer'],
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
        $query = SisccSemestre::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'ano' => SORT_DESC,
                    'semestre' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_semestre' => $this->id_semestre,
            'ano' => $this->ano,
            'semestre' => $this->semestre,
        ]);

        return $dataProvider;
    }
}
