<?php

namespace app\modules\sisai\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisai\models\SisaiQuestionario;

/**
 * SisaiQuestionarioSearch represents the model behind the search form of `app\modules\sisai\models\SisaiQuestionario`.
 */
class SisaiQuestionarioSearch extends SisaiQuestionario
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_questionario', 'tipo_questionario'], 'integer'],
            [['titulo'], 'safe'],
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
        $query = SisaiQuestionario::find();

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
            'id_questionario' => $this->id_questionario,
            'tipo_questionario' => $this->tipo_questionario,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo]);

        return $dataProvider;
    }
}
