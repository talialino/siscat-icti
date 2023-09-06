<?php

namespace app\modules\sispit\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sispit\models\SispitEnsinoOrientacao;

/**
 * SispitEnsinoOrientacaoSearch represents the model behind the search form of `app\modules\sispit\models\SispitEnsinoOrientacao`.
 */
class SispitEnsinoOrientacaoSearch extends SispitEnsinoOrientacao
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ensino_orientacao', 'id_plano_relatorio', 'semestre', 'id_aluno', 'tipo_orientacao', 'carga_horaria', 'pit_rit'], 'integer'],
            [['projeto'], 'safe'],
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
        $query = SispitEnsinoOrientacao::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_ensino_orientacao' => $this->id_ensino_orientacao,
            'id_plano_relatorio' => $this->id_plano_relatorio,
            'semestre' => $this->semestre,
            'id_aluno' => $this->id_aluno,
            'tipo_orientacao' => $this->tipo_orientacao,
            'carga_horaria' => $this->carga_horaria,
            'pit_rit' => $this->pit_rit,
        ]);

        $query->andFilterWhere(['like', 'projeto', $this->projeto]);

        $query->orderBy('semestre ASC, tipo_orientacao ASC');

        return $dataProvider;
    }
}
