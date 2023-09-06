<?php

namespace app\modules\siscc\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SisccParecerSearch represents the model behind the search form of `app\modules\siscc\models\SisccParecer`.
 */
class SisccParecerSearch extends SisccParecer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_parecer', 'id_programa_componente_curricular', 'id_pessoa', 'tipo_parecerista', 'atual'], 'integer'],
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
        $query = SisccParecer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false,
        ]);

        $this->attributes = $params;

        if($this->id_programa_componente_curricular === null){
            // grid filtering conditions
            $query->andFilterWhere([
                'id_pessoa' => $this->id_pessoa,
                'atual' => $this->atual,
            ]);
        }
        else{
            $query->andFilterWhere([
                'id_programa_componente_curricular' => $this->id_programa_componente_curricular,
                'tipo_parecerista' => $this->tipo_parecerista,
            ]);
            $query->andWhere(['not',['parecer' => null]]);
        }

        $query->orderby('data DESC, id_parecer DESC');

        return $dataProvider;
    }

    public function listaPareceres($id_pessoa)
    {
        $query = SisccParecer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false,
        ]);

        $query->joinWith('programaComponenteCurricular As programa');

        $query->andFilterWhere([
            'id_pessoa' => $id_pessoa,
            'atual' => 1,
        ]);

        $query->andWhere('programa.situacao < 11');

        $query->orderby('data DESC, id_parecer DESC');

        return $dataProvider;
    }

    /**
     * Retorna o último parecer recebido pelo SispitPlanoRelatorio. Se $atual for 0, retorna o penúltimo.
     * @param int $id_programa_componente_curricular
     * @param int $atual
     * @return SisccParecer 
     */
    public static function ultimoParecer($id_programa_componente_curricular, $atual = 1)
    {
        return SisccParecer::find()->andFilterWhere(['id_programa_componente_curricular' => $id_programa_componente_curricular, 'atual' => $atual])->orderBy(['id_parecer' => SORT_DESC])->one();
    }
}
