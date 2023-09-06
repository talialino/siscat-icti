<?php

namespace app\modules\sisai\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisai\models\SisaiPergunta;

/**
 * SisaiPerguntaSearch represents the model behind the search form of `app\modules\sisai\models\SisaiPergunta`.
 */
class SisaiPerguntaSearch extends SisaiPergunta
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pergunta', 'tipo_pergunta', 'id_grupo_perguntas', 'nsa'], 'integer'],
            [['descricao'], 'safe'],
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
        $query = SisaiPergunta::find();

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
            'id_pergunta' => $this->id_pergunta,
            'tipo_pergunta' => $this->tipo_pergunta,
            'id_grupo_perguntas' => $this->id_grupo_perguntas,
            'nsa' => $this->nsa,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao]);

        return $dataProvider;
    }
}
