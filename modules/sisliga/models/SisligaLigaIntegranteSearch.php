<?php

namespace app\modules\sisliga\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SisligaLigaIntegranteSearch represents the model behind the search form of `app\modules\sisliga\models\SisligaLigaIntegrante`.
 */
class SisligaLigaIntegranteSearch extends SisligaLigaIntegrante
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_liga_integrante', 'id_liga_academica', 'id_integrante_externo', 'id_pessoa', 'id_aluno', 'carga_horaria'], 'integer'],
            [['funcao'], 'safe'],
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
        $query = SisligaLigaIntegrante::find();

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
            'id_liga_integrante' => $this->id_liga_integrante,
            'id_liga_academica' => $this->id_liga_academica,
            'id_integrante_externo' => $this->id_integrante_externo,
            'id_pessoa' => $this->id_pessoa,
            'id_aluno' => $this->id_aluno,
            'carga_horaria' => $this->carga_horaria,
        ]);

        $query->andFilterWhere(['like', 'funcao', $this->funcao]);

        return $dataProvider;
    }
}
