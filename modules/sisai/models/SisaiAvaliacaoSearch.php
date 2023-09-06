<?php

namespace app\modules\sisai\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisai\models\SisaiAvaliacao;

/**
 * SisaiAvaliacaoSearch represents the model behind the search form of `app\modules\sisai\models\SisaiAvaliacao`.
 */
class SisaiAvaliacaoSearch extends SisaiAvaliacao
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_avaliacao', 'id_semestre', 'id_aluno', 'id_pessoa', 'tipo_avaliacao', 'situacao'], 'integer'],
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
        $query = SisaiAvaliacao::find();

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

        if($this->situacao == 0)
            unset($this->situacao);

        // grid filtering conditions
        $query->andFilterWhere([
            'id_avaliacao' => $this->id_avaliacao,
            'id_semestre' => $this->id_semestre,
            'id_aluno' => $this->id_aluno,
            'id_pessoa' => $this->id_pessoa,
            'tipo_avaliacao' => $this->tipo_avaliacao,
            'situacao' => $this->situacao,
        ]);

        return $dataProvider;
    }
}
