<?php

namespace app\modules\siscc\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\siscc\models\SisccProgramaComponenteCurricularPessoa;

/**
 * SisccProgramaComponenteCurricularPessoaSearch represents the model behind the search form of `app\modules\siscc\models\SisccProgramaComponenteCurricularPessoa`.
 */
class SisccProgramaComponenteCurricularPessoaSearch extends SisccProgramaComponenteCurricularPessoa
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_programa_componente_curricular', 'id_pessoa', 'programaComponenteCurricular.id_semestre', 'programaComponenteCurricular.id_componente_curricular', 'programaComponenteCurricular.id_setor'], 'integer'],
           //[[ 'programaComponenteCurricular.id_semestre', 'programaComponenteCurricular.id_componente_curricular', 'programaComponenteCurricular.id_setor'],'safe'],
        ];
    }

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['programaComponenteCurricular.id_semestre', 'programaComponenteCurricular.id_componente_curricular', 'programaComponenteCurricular.id_setor']);
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
    public function search($id_pessoa, $params)
    {
        $query = SisccProgramaComponenteCurricularPessoa::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'semestre.ano' => SORT_DESC,
                    'semestre.semestre' => SORT_DESC,
                ]
            ],
        ]);

        $this->id_pessoa = $id_pessoa;
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_pessoa' => $this->id_pessoa,
        ]);

        $query->joinWith('programaComponenteCurricular As programaComponenteCurricular');
        $query->joinWith('semestre As semestre');

        $query->andFilterWhere(['like', 'programaComponenteCurricular.id_semestre', $this->getAttribute('programaComponenteCurricular.id_semestre')])
            ->andFilterWhere(['like', 'programaComponenteCurricular.id_componente_curricular', $this->getAttribute('programaComponenteCurricular.id_componente_curricular')])
            ->andFilterWhere(['like', 'programaComponenteCurricular.id_setor', $this->getAttribute('programaComponenteCurricular.id_setor')]);

        $dataProvider->sort->attributes['semestre.ano'] = [
            'asc' => ['semestre.ano' => SORT_ASC],
            'desc' => ['semestre.ano' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['semestre.semestre'] = [
            'asc' => ['semestre.semestre' => SORT_ASC],
            'desc' => ['semestre.semestre' => SORT_DESC],
        ];

        return $dataProvider;
    }

    public static function searchSemestre($id_semestre)
    {
        $query = SisccProgramaComponenteCurricularPessoa::find();

        $query->joinWith('programaComponenteCurricular As programaComponenteCurricular');

        $query->andFilterWhere(['like', 'programaComponenteCurricular.id_semestre', $id_semestre]);

        return $query->all();
    }
}
