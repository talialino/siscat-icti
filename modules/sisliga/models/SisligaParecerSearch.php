<?php

namespace app\modules\sisliga\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SisligaParecerSearch represents the model behind the search form of `app\modules\sispit\models\SisligaParecer`.
 */
class SisligaParecerSearch extends SisligaParecer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_parecer', 'id_liga_academica', 'id_relatorio', 'id_pessoa', 'atual'], 'integer'],
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
        $query = SisligaParecer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false,
        ]);

        $this->attributes = $params;

        if($this->id_liga_academica === null && $this->id_relatorio === null){
            // grid filtering conditions
            $query->andFilterWhere([
                'id_pessoa' => $this->id_pessoa,
                'atual' => $this->atual,
            ]);
        }
        else{
            $query->andFilterWhere([
                'id_liga_academica' => $this->id_liga_academica,
                'id_relatorio' => $this->id_relatorio,
            ]);
            $query->andWhere(['not',['parecer' => null]]);
        }

        $query->orderby('data DESC, id_parecer DESC');

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function listaPareceres($id_pessoa)
    {
        $query = SisligaParecer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false,
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'sisliga_parecer.id_pessoa' => $id_pessoa,
            'atual' => 1,
        ]);

        $query->andWhere('(sisliga_parecer.id_liga_academica IS NOT NULL AND ligaAcademica.situacao < 6) OR (sisliga_parecer.id_relatorio IS NOT NULL AND relatorio.situacao < 6)');

        $query->joinWith('ligaAcademica As ligaAcademica');
        $query->joinWith('relatorio As relatorio');

        $query->orderby('data DESC, id_parecer DESC');

        return $dataProvider;
    }
}
