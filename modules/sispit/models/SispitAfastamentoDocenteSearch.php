<?php

namespace app\modules\sispit\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sispit\models\SispitAfastamentoDocente;

/**
 * SispitAfastamentoDocenteSearch represents the model behind the search form of `app\modules\sispit\models\SispitAfastamentoDocente`.
 */
class SispitAfastamentoDocenteSearch extends SispitAfastamentoDocente
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_afastamento_docente', 'id_plano_relatorio', 'semestre', 'nivel_graduacao', 'carga_horaria', 'pit_rit', 'eh_afastamento'], 'integer'],
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
        $query = SispitAfastamentoDocente::find();

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
            'id_afastamento_docente' => $this->id_afastamento_docente,
            'id_plano_relatorio' => $this->id_plano_relatorio,
            'semestre' => $this->semestre,
            'nivel_graduacao' => $this->nivel_graduacao,
            'carga_horaria' => $this->carga_horaria,
            'pit_rit' => $this->pit_rit,
            'eh_afastamento' => $this->eh_afastamento,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao]);

        $query->orderBy('semestre ASC');

        return $dataProvider;
    }
}
