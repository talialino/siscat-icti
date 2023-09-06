<?php

namespace app\modules\sispit\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sispit\models\SispitPlanoRelatorio;

/**
 * SispitPlanoRelatorioSearch represents the model behind the search form of `app\modules\sispit\models\SispitPlanoRelatorio`.
 */
class SispitPlanoRelatorioSearch extends SispitPlanoRelatorio
{
    public $parecerista;

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['pessoa.nome']);
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plano_relatorio', 'user_id', 'id_ano', 'status', 'parecerista'], 'integer'],
            [['observacao_pit','observacao_rit','pessoa.nome'],'string'],
            [['data_homologacao_nucleo_pit', 'data_homologacao_cac_pit', 'data_preenchimento_pit', 'data_homologacao_nucleo_rit', 'data_homologacao_cac_rit', 'data_preenchimento_rit'], 'safe'],
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
        $query = SispitPlanoRelatorio::find();

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
            'id_plano_relatorio' => $this->id_plano_relatorio,
            'user_id' => $this->user_id,
            'id_ano' => $this->id_ano,
            'observacao_pit' => $this->observacao_pit,
            'observacao_rit' => $this->observacao_rit,
            'data_homologacao_nucleo_pit' => $this->data_homologacao_nucleo_pit,
            'data_homologacao_cac_pit' => $this->data_homologacao_cac_pit,
            'data_preenchimento_pit' => $this->data_preenchimento_pit,
            'data_homologacao_nucleo_rit' => $this->data_homologacao_nucleo_rit,
            'data_homologacao_cac_rit' => $this->data_homologacao_cac_rit,
            'data_preenchimento_rit' => $this->data_preenchimento_rit,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }

    /**
     * Cria uma instância data provider com todos os projetos em avaliação pela CAC
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchAvaliacaoCac($params)
    {
        $query = SispitPlanoRelatorio::find();

        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50],
            'sort' => [
                'defaultOrder' => [
                    'id_plano_relatorio' => SORT_DESC,
                ]
            ],
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if($this->status == null)
            $query->andWhere(['status' => [5,6,7,8,15,16,17,18]]);
        
        $query->leftJoin('sisrh_pessoa pessoa','user_id = id_user');

        if($this->parecerista)
            $query->innerJoin('sispit_parecer pp',
                "sispit_plano_relatorio.id_plano_relatorio = pp.id_plano_relatorio
                AND pp.atual =1 AND pp.tipo_parecerista =2
                AND pp.id_pessoa = $this->parecerista AND pp.pit_rit = IF(status < 10, 0, 1 )"
            );

        $query->andFilterWhere([
            'id_ano' => $this->id_ano,
            'status' => $this->status,
        ])
        ->andFilterWhere(['like', 'pessoa.nome', $this->getAttribute('pessoa.nome')]);

        $dataProvider->sort->attributes['pessoa.nome'] = [
            'asc' => ['pessoa.nome' => SORT_ASC],
            'desc' => ['pessoa.nome' => SORT_DESC],
        ];

        return $dataProvider;
    }
}
