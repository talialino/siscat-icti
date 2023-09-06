<?php

namespace app\modules\sisape\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sisape\models\SisapeRelatorio;

/**
 * SisapeRelatorioSearch represents the model behind the search form of `app\modules\sisape\models\SisapeRelatorio`.
 */
class SisapeRelatorioSearch extends SisapeRelatorio
{
    /**
     * Atributo utilizado para pesquisar todos os relatórios de uma pessoa
     */
    public $id_pessoa;

    /**
     * Atributo utilizado para pesquisar todos os relatórios de um núcleo
     */
    public $id_setor;

    public $tipo_projeto;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_relatorio', 'id_projeto', 'situacao_projeto', 'alunos_orientados', 'resumos_publicados', 'artigos_publicados', 'artigos_aceitos', 'relatorio_agencia', 'deposito_patente', 'sessao_congregacao', 'tipo_sessao_congregacao', 'situacao'], 'integer'],
            [['justificativa','outros_indicadores', 'consideracoes_finais', 'data_relatorio', 'data_aprovacao_nucleo', 'data_aprovacao_copex', 'data_homologacao_congregacao', 'id_pessoa', 'tipo_projeto', 'id_setor'], 'safe'],
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
    public function search($params, $meusprogramas = false)
    {
        $query = SisapeRelatorio::find();

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

        //Comentado em 20/09/2021 por conta da remoção da tramitação
        //if(!$meusprogramas && $this->situacao == null)
        //    $query->andWhere(['<','sisape_relatorio.situacao',13]);

        $query->innerJoinWith('projeto AS projeto');
        //$query->select('sisape_relatorio.*, projeto.id_pessoa, projeto.tipo_projeto, projeto.id_setor, projeto.titulo');

        // grid filtering conditions
        $query->andFilterWhere([
            'id_relatorio' => $this->id_relatorio,
            'sisape_relatorio.id_projeto' => $this->id_projeto,
            'projeto.id_pessoa' => $this->id_pessoa,
            'projeto.id_setor' => $this->id_setor,
            'projeto.tipo_projeto' => $this->tipo_projeto,
            'situacao_projeto' => $this->situacao_projeto,
            'data_relatorio' => $this->data_relatorio,
            'sisape_relatorio.data_aprovacao_nucleo' => $this->data_aprovacao_nucleo,
            'sisape_relatorio.data_aprovacao_copex' => $this->data_aprovacao_copex,
            'sisape_relatorio.data_homologacao_congregacao' => $this->data_homologacao_congregacao,
            'sisape_relatorio.sessao_congregacao' => $this->sessao_congregacao,
            'sisape_relatorio.tipo_sessao_congregacao' => $this->tipo_sessao_congregacao,
            'sisape_relatorio.situacao' => $this->situacao,
        ]);

        return $dataProvider;
    }
}
