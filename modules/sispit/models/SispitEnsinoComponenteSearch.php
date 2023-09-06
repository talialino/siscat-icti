<?php

namespace app\modules\sispit\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sispit\models\SispitEnsinoComponente;

/**
 * SispitEnsinoComponenteSearch represents the model behind the search form of `app\modules\sispit\models\SispitEnsinoComponente`.
 */
class SispitEnsinoComponenteSearch extends SispitEnsinoComponente
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ensino_componente', 'id_plano_relatorio', 'id_componente_curricular', 'nivel_graduacao', 'semestre', 'ch_teorica', 'ch_pratica', 'ch_estagio', 'pit_rit'], 'integer'],
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
        $query = SispitEnsinoComponente::find();

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
            'id_ensino_componente' => $this->id_ensino_componente,
            'id_plano_relatorio' => $this->id_plano_relatorio,
            'id_componente_curricular' => $this->id_componente_curricular,
            'nivel_graduacao' => $this->nivel_graduacao,
            'semestre' => $this->semestre,
            'ch_teorica' => $this->ch_teorica,
            'ch_pratica' => $this->ch_pratica,
            'ch_estagio' => $this->ch_estagio,
            'pit_rit' => $this->pit_rit,
        ]);

        $query->orderBy('semestre ASC, nivel_graduacao ASC');

        return $dataProvider;
    }
}
