<?php

namespace app\modules\sisrh\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisrh\models\SisrhComissaoPessoa;

/**
 * SisrhComissaoPessoaSearch represents the model behind the search form of `app\modules\sisrh\models\SisrhComissaoPessoa`.
 */
class SisrhComissaoPessoaSearch extends SisrhComissaoPessoa
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_comissao', 'id_pessoa', 'funcao'], 'integer'],
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
    public function search($id_comissao)
    {
        $query = SisrhComissaoPessoa::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_comissao' => $id_comissao,
            'id_pessoa' => $this->id_pessoa,
            'funcao' => $this->funcao,
        ]);

        $query->orderby('funcao');

        return $dataProvider;
    }
}
