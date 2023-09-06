<?php

namespace app\modules\sisrh\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisrh\models\SisrhClasseFuncional;

/**
 * SisrhClasseFuncionalSearch represents the model behind the search form of `app\modules\sisrh\models\SisrhClasseFuncional`.
 */
class SisrhClasseFuncionalSearch extends SisrhClasseFuncional
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_classe_funcional', 'id_categoria'], 'integer'],
            [['denominacao'], 'safe'],
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
        $query = SisrhClasseFuncional::find();

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
            'id_classe_funcional' => $this->id_classe_funcional,
            'id_categoria' => $this->id_categoria,
        ]);

        $query->andFilterWhere(['like', 'denominacao', $this->denominacao]);

        return $dataProvider;
    }
}
