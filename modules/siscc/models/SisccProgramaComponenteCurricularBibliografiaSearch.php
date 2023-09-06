<?php

namespace app\modules\siscc\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\siscc\models\SisccProgramaComponenteCurricularBibliografia;

/**
 * SisccProgramaComponenteCurricularBibliografiaSearch represents the model behind the search form of `app\modules\siscc\models\SisccProgramaComponenteCurricularBibliografia`.
 */
class SisccProgramaComponenteCurricularBibliografiaSearch extends SisccProgramaComponenteCurricularBibliografia
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['programaComponenteCurricular.id_semestre'], 'integer'],
        ];
    }

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['programaComponenteCurricular.id_semestre']);
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
        $query = SisccProgramaComponenteCurricularBibliografia::find();

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
        $query->joinWith('programaComponenteCurricular As programaComponenteCurricular');

        $query->andWhere(['programaComponenteCurricular.id_semestre' => $this->getAttribute('programaComponenteCurricular.id_semestre')]);
        $query->andWhere(['programaComponenteCurricular.situacao' => 11]);//somente referencias de programas aprovados será retornado

        return $dataProvider;
    }
}
