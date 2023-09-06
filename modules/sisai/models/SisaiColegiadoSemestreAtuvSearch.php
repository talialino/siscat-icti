<?php

namespace app\modules\sisai\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisai\models\SisaiColegiadoSemestreAtuv;

/**
 * SisaiColegiadoSemestreAtuvSearch represents the model behind the search form of `app\modules\sisai\models\SisaiColegiadoSemestreAtuv`.
 */
class SisaiColegiadoSemestreAtuvSearch extends SisaiColegiadoSemestreAtuv
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_colegiado_semestre_atuv', 'id_semestre'], 'integer'],
            [['colegiados_liberados'], 'safe'],
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
        $query = SisaiColegiadoSemestreAtuv::find();

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
            'id_colegiado_semestre_atuv' => $this->id_colegiado_semestre_atuv,
            'id_semestre' => $this->id_semestre,
        ]);

        $query->andFilterWhere(['like', 'colegiados_liberados', $this->colegiados_liberados]);

        return $dataProvider;
    }
}
