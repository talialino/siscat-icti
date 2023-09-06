<?php

namespace app\modules\sispit\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sispit\models\SispitMonitoria;

/**
 * SispitMonitoriaSearch represents the model behind the search form of `app\modules\sispit\models\SispitMonitoria`.
 */
class SispitMonitoriaSearch extends SispitMonitoria
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_monitoria', 'id_plano_relatorio', 'semestre', 'id_aluno', 'id_componente_curricular', 'carga_horaria', 'pit_rit'], 'integer'],
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
        $query = SispitMonitoria::find();

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
            'id_monitoria' => $this->id_monitoria,
            'id_plano_relatorio' => $this->id_plano_relatorio,
            'semestre' => $this->semestre,
            'id_aluno' => $this->id_aluno,
            'id_componente_curricular' => $this->id_componente_curricular,
            'carga_horaria' => $this->carga_horaria,
            'pit_rit' => $this->pit_rit,
        ]);

        $query->orderBy('semestre ASC');

        return $dataProvider;
    }
}
