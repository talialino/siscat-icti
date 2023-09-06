<?php

namespace app\modules\sisrh\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisrh\models\SisrhSetorMembroAutomatico;

/**
 * SisrhSetorMembroAutomaticoSearch represents the model behind the search form of `app\modules\sisrh\models\SisrhSetorMembroAutomatico`.
 */
class SisrhSetorMembroAutomaticoSearch extends SisrhSetorMembroAutomatico
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_membro_automatico', 'id_setor_origem', 'funcao_origem', 'id_setor_destino', 'funcao_destino'], 'integer'],
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
        $query = SisrhSetorMembroAutomatico::find();

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
            'id_membro_automatico' => $this->id_membro_automatico,
            'id_setor_origem' => $this->id_setor_origem,
            'funcao_origem' => $this->funcao_origem,
            'id_setor_destino' => $this->id_setor_destino,
            'funcao_destino' => $this->funcao_destino,
        ]);

        return $dataProvider;
    }
}
