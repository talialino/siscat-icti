<?php

namespace app\modules\sispit\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sispit\models\SispitPublicacao;

/**
 * SispitPublicacaoSearch represents the model behind the search form of `app\modules\sispit\models\SispitPublicacao`.
 */
class SispitPublicacaoSearch extends SispitPublicacao
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_publicacao', 'id_plano_relatorio', 'semestre', 'pit_rit'], 'integer'],
            [['titulo', 'editora', 'local', 'fonte_financiadora'], 'safe'],
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
        $query = SispitPublicacao::find();

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
            'id_publicacao' => $this->id_publicacao,
            'id_plano_relatorio' => $this->id_plano_relatorio,
            'semestre' => $this->semestre,
            'pit_rit' => $this->pit_rit,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'editora', $this->editora])
            ->andFilterWhere(['like', 'local', $this->local])
            ->andFilterWhere(['like', 'fonte_financiadora', $this->fonte_financiadora]);
        
        $query->orderBy('semestre ASC');

        return $dataProvider;
    }
}
