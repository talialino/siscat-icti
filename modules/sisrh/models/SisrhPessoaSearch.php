<?php

namespace app\modules\sisrh\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisrh\models\SisrhPessoa;

/**
 * SisrhPessoaSearch represents the model behind the search form of `app\modules\sisrh\models\SisrhPessoa`.
 */
class SisrhPessoaSearch extends SisrhPessoa
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pessoa', 'id_user', 'siape', 'estado_civil', 'escolaridade', 'regime_trabalho', 'id_cargo', 'id_classe_funcional', 'situacao'], 'integer'],
            [['nome', 'dt_nascimento', 'sexo', 'telefone', 'emails', 'dt_ingresso_orgao', 'dt_exercicio', 'dt_vigencia'], 'safe'],
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
    public function search($params, $categoria = null)
    {
        $query = SisrhPessoa::find();


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
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
            'id_pessoa' => $this->id_pessoa,
            'id_user' => $this->id_user,
            'siape' => $this->siape,
            'dt_nascimento' => $this->dt_nascimento,
            'estado_civil' => $this->estado_civil,
            'escolaridade' => $this->escolaridade,
            'regime_trabalho' => $this->regime_trabalho,
            'dt_ingresso_orgao' => $this->dt_ingresso_orgao,
            'dt_exercicio' => $this->dt_exercicio,
            'dt_vigencia' => $this->dt_vigencia,
            'sisrh_pessoa.id_cargo' => $this->id_cargo,
            'id_classe_funcional' => $this->id_classe_funcional,
            'situacao' => $this->situacao,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'sexo', $this->sexo])
            ->andFilterWhere(['like', 'telefone', $this->telefone])
            ->andFilterWhere(['like', 'emails', $this->emails]);

        if(is_numeric($categoria))
        {
            $query->innerjoinWith('cargo');
            $query->andWhere('id_categoria = '.$categoria);
        }

        $query->orderBy('nome ASC');

        return $dataProvider;
    }
}
