<?php

namespace app\modules\sisrh\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisrh\models\SisrhComissao;

/**
 * SisrhComissaoSearch represents the model behind the search form of `app\modules\sisrh\models\SisrhComissao`.
 */
class SisrhComissaoSearch extends SisrhComissao
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_comissao'], 'integer'],
            [['nome', 'sigla', 'data_inicio', 'data_fim', 'observacao'], 'safe'],
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
        $query = SisrhComissao::find();

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
            'id_comissao' => $this->id_comissao,
            'data_inicio' => $this->data_inicio,
            'data_fim' => $this->data_fim,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'sigla', $this->sigla])
            ->andFilterWhere(['like', 'observacao', $this->observacao]);

        return $dataProvider;
    }
}
