<?php

namespace app\modules\sisai\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisai\models\SisaiAluno;

/**
 * SisaiAlunoSearch represents the model behind the search form of `app\modules\sisai\models\SisaiAluno`.
 */
class SisaiAlunoSearch extends SisaiAluno
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_aluno', 'matricula', 'id_setor', 'ativo', 'id_semestre', 'nivel_escolaridade'], 'integer'],
            [['nome', 'email'], 'safe'],
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
        $query = SisaiAluno::find();

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
            'id_aluno' => $this->id_aluno,
            'matricula' => $this->matricula,
            'id_setor' => $this->id_setor,
            'id_semestre' => $this->id_semestre,
            'nivel_escolaridade' => $this->nivel_escolaridade,
            'ativo' => $this->ativo,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
