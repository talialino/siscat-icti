<?php

namespace app\modules\sispit\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sispit\models\SispitAtividadesAdministrativas;

/**
 * SispitAtividadesAdministrativasSearch represents the model behind the search form of `app\modules\sispit\models\SispitAtividadesAdministrativas`.
 */
class SispitAtividadesAdministrativasSearch extends SispitAtividadesAdministrativas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_atividades_administrativas', 'id_plano_relatorio', 'tipo_atividade', 'semestre', 'carga_horaria', 'pit_rit'], 'integer'],
            [['descricao'], 'safe'],
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
        $query = SispitAtividadesAdministrativas::find();

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
            'id_atividades_administrativas' => $this->id_atividades_administrativas,
            'id_plano_relatorio' => $this->id_plano_relatorio,
            'tipo_atividade' => $this->tipo_atividade,
            'semestre' => $this->semestre,
            'carga_horaria' => $this->carga_horaria,
            'pit_rit' => $this->pit_rit,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao]);

        $query->orderBy('semestre ASC');

        return $dataProvider;
    }
}
