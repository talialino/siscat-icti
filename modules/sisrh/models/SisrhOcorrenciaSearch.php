<?php

namespace app\modules\sisrh\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisrh\models\SisrhOcorrencia;

/**
 * SisrhOcorrenciaSearch represents the model behind the search form of `app\modules\sisrh\models\SisrhOcorrencia`.
 */
class SisrhOcorrenciaSearch extends SisrhOcorrencia
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ocorrencia'], 'integer'],
            [['justificativa'], 'safe'],
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
        $query = SisrhOcorrencia::find();

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
            'id_ocorrencia' => $this->id_ocorrencia,
        ]);

        $query->andFilterWhere(['like', 'justificativa', $this->justificativa]);

        $query->orderby('justificativa ASC');

        return $dataProvider;
    }
}
