<?php

namespace app\modules\sisrh\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisrh\models\SisrhSetor;

/**
 * SisrhSetorSearch represents the model behind the search form of `app\modules\sisrh\models\SisrhSetor`.
 */
class SisrhSetorSearch extends SisrhSetor
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_setor', 'id_setor_responsavel', 'codigo', 'eh_colegiado', 'eh_nucleo_academico'], 'integer'],
            [['nome', 'sigla', 'observacao'], 'safe'],
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
        $query = SisrhSetor::find();

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
            'id_setor' => $this->id_setor,
            'id_setor_responsavel' => $this->id_setor_responsavel,
            'codigo' => $this->codigo,
            'eh_colegiado' => $this->eh_colegiado,
            'eh_nucleo_academico' => $this->eh_nucleo_academico,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'sigla', $this->sigla])
            ->andFilterWhere(['observacao' => $this->observacao]);

        //$query->orderby('id_setor_responsavel','id_setor');

        return $dataProvider;
    }
}
