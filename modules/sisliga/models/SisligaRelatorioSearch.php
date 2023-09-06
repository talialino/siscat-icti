<?php

namespace app\modules\sisliga\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisliga\models\SisligaRelatorio;

/**
 * SisligaRelatorioSearch represents the model behind the search form of `app\modules\sisliga\models\SisligaRelatorio`.
 */
class SisligaRelatorioSearch extends SisligaRelatorio
{
    /**
     * Atributo utilizado para pesquisar todos os relatórios de uma pessoa
     */
    public $id_pessoa;

    /**
     * Atributo utilizado para pesquisar todos os relatórios de um núcleo
     */
    public $id_setor;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_relatorio', 'id_liga_academica', 'sessao_congregacao', 'tipo_sessao_congregacao', 'situacao'], 'integer'],
            [['prestacao_contas','atividades', 'consideracoes_finais', 'data_relatorio', 'data_aprovacao_comissao', 'data_homologacao_congregacao', 'id_pessoa'], 'safe'],
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
        $query = SisligaRelatorio::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_relatorio' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->innerJoinWith('ligaAcademica AS ligaAcademica');
        //$query->select('sisliga_relatorio.*, ligaAcademica.id_pessoa, ligaAcademica.nome');

        // grid filtering conditions
        $query->andFilterWhere([
            'id_relatorio' => $this->id_relatorio,
            'sisliga_relatorio.id_liga_academica' => $this->id_liga_academica,
            'ligaAcademica.id_pessoa' => $this->id_pessoa,
            'data_relatorio' => $this->data_relatorio,
            'sisliga_relatorio.data_aprovacao_comissao' => $this->data_aprovacao_comissao,
            'sisliga_relatorio.data_homologacao_congregacao' => $this->data_homologacao_congregacao,
            'sisliga_relatorio.sessao_congregacao' => $this->sessao_congregacao,
            'sisliga_relatorio.tipo_sessao_congregacao' => $this->tipo_sessao_congregacao,
            'sisliga_relatorio.situacao' => $this->situacao,
        ]);

        return $dataProvider;
    }
}
