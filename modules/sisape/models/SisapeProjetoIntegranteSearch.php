<?php

namespace app\modules\sisape\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisape\models\SisapeProjetoIntegrante;

/**
 * SisapeProjetoIntegranteSearch represents the model behind the search form of `app\modules\sisape\models\SisapeProjetoIntegrante`.
 */
class SisapeProjetoIntegranteSearch extends SisapeProjetoIntegrante
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_projeto_integrante', 'id_projeto', 'id_integrante_externo', 'id_pessoa', 'id_aluno', 'carga_horaria'], 'integer'],
            [['funcao', 'vinculo'], 'safe'],
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
        $query = SisapeProjetoIntegrante::find();

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
            'id_projeto_integrante' => $this->id_projeto_integrante,
            'id_projeto' => $this->id_projeto,
            'id_integrante_externo' => $this->id_integrante_externo,
            'id_pessoa' => $this->id_pessoa,
            'id_aluno' => $this->id_aluno,
            'carga_horaria' => $this->carga_horaria,
        ]);

        $query->andFilterWhere(['like', 'funcao', $this->funcao])
            ->andFilterWhere(['like', 'vinculo', $this->vinculo]);

        return $dataProvider;
    }
}
