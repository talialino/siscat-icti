<?php

namespace app\modules\siscc\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\siscc\models\SisccComponenteCurricular;

/**
 * SisccComponenteCurricularSearch represents the model behind the search form of `app\modules\siscc\models\SisccComponenteCurricular`.
 */
class SisccComponenteCurricularSearch extends SisccComponenteCurricular
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_componente_curricular', 'ch_teorica', 'ch_pratica', 'ch_estagio', 'modulo_teoria', 'modulo_pratica', 'modulo_estagio', 'ativo'], 'integer'],
            [['nome', 'codigo_componente', 'ementa'], 'safe'],
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
        $query = SisccComponenteCurricular::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'id_componente_curricular' => $this->id_componente_curricular,
            'ch_teorica' => $this->ch_teorica,
            'ch_pratica' => $this->ch_pratica,
            'ch_estagio' => $this->ch_estagio,
            'modulo_teoria' => $this->modulo_teoria,
            'modulo_pratica' => $this->modulo_pratica,
            'modulo_estagio' => $this->modulo_estagio,
            'ativo' => $this->ativo,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'codigo_componente', $this->codigo_componente])
            ->andFilterWhere(['like', 'ementa', $this->ementa]);

        return $dataProvider;
    }
}
