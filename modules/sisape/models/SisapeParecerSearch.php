<?php

namespace app\modules\sisape\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SisapeParecerSearch represents the model behind the search form of `app\modules\sispit\models\SisapeParecer`.
 */
class SisapeParecerSearch extends SisapeParecer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_parecer', 'id_projeto', 'id_relatorio', 'id_pessoa', 'tipo_parecerista', 'atual'], 'integer'],
            [['parecer', 'data'], 'safe'],
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
        $query = SisapeParecer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false,
        ]);

        $this->attributes = $params;

        if($this->id_projeto === null && $this->id_relatorio === null){
            // grid filtering conditions
            $query->andFilterWhere([
                'id_pessoa' => $this->id_pessoa,
                'atual' => $this->atual,
            ]);
        }
        else{
            $query->andFilterWhere([
                'id_projeto' => $this->id_projeto,
                'id_relatorio' => $this->id_relatorio,
                'tipo_parecerista' => $this->tipo_parecerista,
            ]);
            $query->andWhere(['not',['parecer' => null]]);
        }

        $query->orderby('data DESC, id_parecer DESC');

        return $dataProvider;
    }
}
