<?php

namespace app\modules\sisrh\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisrh\models\SisrhPessoa;

/**
 * SisrhPessoaSearch represents the model behind the search form of `app\modules\sisrh\models\SisrhPessoa`.
 */
class SisrhContatosSearch extends SisrhPessoa
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['cargo.descricao','categoria.nome']);
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pessoa', 'id_user', 'siape', 'id_cargo', 'situacao'], 'integer'],
            [['nome', 'telefone', 'emails','cargo.descricao','categoria.nome',], 'safe'],
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
     * @param int $categoria - usado para pesquisar todas as pessoas que pertencem à categoria especificada
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SisrhPessoa::find();


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => [
                    'nome' => SORT_ASC,
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
            'siape' => $this->siape,
            'sisrh_pessoa.id_cargo' => $this->id_cargo,
            'situacao' => $this->situacao,
        ]);

        $query->andFilterWhere(['like', 'sisrh_pessoa.nome', $this->nome])
            ->andFilterWhere(['like', 'telefone', $this->telefone])
            ->andFilterWhere(['like', 'emails', $this->emails])
            ->andFilterWhere(['like', 'cargo.descricao', $this->getAttribute('cargo.descricao')])
            ->andFilterWhere(['like', 'categoria.nome', $this->getAttribute('categoria.nome')]);

        $query->joinWith('cargo As cargo');
        $query->joinWith('categoria As categoria');

        $dataProvider->sort->attributes['cargo.descricao'] = [
            'asc' => ['cargo.descricao' => SORT_ASC],
            'desc' => ['cargo.descricao' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['categoria.nome'] = [
            'asc' => ['categoria.nome' => SORT_ASC],
            'desc' => ['categoria.nome' => SORT_DESC],
        ];

        return $dataProvider;
    }
}
