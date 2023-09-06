<?php

namespace app\modules\sisrh\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisrh\models\SisrhAfastamento;

/**
 * SisrhAfastamentoSearch represents the model behind the search form of `app\modules\sisrh\models\SisrhAfastamento`.
 */
class SisrhAfastamentoSearch extends SisrhAfastamento
{
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['pessoa.id_pessoa', 'pessoa.nome', 'pessoa.situacao',
            'cargo.id_cargo', 'cargo.id_categoria', 'setor.id_setor']);
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_afastamento', 'id_pessoa','id_ocorrencia'], 'integer'],
            [['inicio', 'termino', 'observacao', 'pessoa.id_pessoa','cargo.id_cargo','pessoa.nome',
                'cargo.id_categoria', 'setor.id_setor'], 'safe'],
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
        $query = SisrhAfastamento::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('pessoa As pessoa');
        $query->joinWith('cargo As cargo');
        $query->joinWith('setor As setor');

        // grid filtering conditions
        $query->andFilterWhere([
            'id_afastamento' => $this->id_afastamento,
            'id_pessoa' => $this->id_pessoa,
            //'inicio' => $this->inicio,
            //'termino' => $this->termino,
            'id_ocorrencia' => $this->id_ocorrencia,
            //'observacao' => $this->observacao,
        ]);
        $query->andWhere(['OR','termino IS NULL','termino >= CURDATE()']);
        $query->andFilterWhere(['cargo.id_cargo' => $this->getAttribute('cargo.id_cargo')])
        ->andFilterWhere(['like', 'pessoa.nome', $this->getAttribute('pessoa.nome')])
        ->andFilterWhere(['cargo.id_categoria' => $this->getAttribute('cargo.id_categoria')])
        ->andFilterWhere(['setor.id_setor' => $this->getAttribute('setor.id_setor')]);

        return $dataProvider;
    }
}
