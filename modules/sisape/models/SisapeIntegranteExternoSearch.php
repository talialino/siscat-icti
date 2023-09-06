<?php

namespace app\modules\sisape\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisape\models\SisapeIntegranteExterno;

/**
 * SisapeIntegranteExternoSearch represents the model behind the search form of `app\modules\sisape\models\SisapeIntegranteExterno`.
 */
class SisapeIntegranteExternoSearch extends SisapeIntegranteExterno
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_integrante_externo'], 'integer'],
            [['nome', 'email', 'telefone'], 'safe'],
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
        $query = SisapeIntegranteExterno::find();

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
            'id_integrante_externo' => $this->id_integrante_externo,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'telefone', $this->telefone]);

        return $dataProvider;
    }
}
