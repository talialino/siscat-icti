<?php

namespace app\modules\sispit\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sispit\models\SispitPesquisaExtensao;
use app\modules\sisape\models\SisapeProjeto;

/**
 * SispitPesquisaExtensaoSearch represents the model behind the search form of `app\modules\sispit\models\SispitPesquisaExtensao`.
 */
class SispitPesquisaExtensaoSearch extends SispitPesquisaExtensao
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pesquisa_extensao', 'id_plano_relatorio', 'id_projeto', 'semestre', 'tipo_participacao', 'carga_horaria', 'pit_rit'], 'integer'],
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
        $query = SispitPesquisaExtensao::find();

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

        $query->joinWith('projeto as projeto');

        // grid filtering conditions
        $query->andFilterWhere([
            'id_pesquisa_extensao' => $this->id_pesquisa_extensao,
            'id_plano_relatorio' => $this->id_plano_relatorio,
            'sispit_pesquisa_extensao.id_projeto' => $this->id_projeto,
            'semestre' => $this->semestre,
            'tipo_participacao' => $this->tipo_participacao,
            'carga_horaria' => $this->carga_horaria,
            'pit_rit' => $this->pit_rit,
        ]);

        $query->orderBy('semestre ASC, projeto.tipo_projeto ASC');

        return $dataProvider;
    }
}
