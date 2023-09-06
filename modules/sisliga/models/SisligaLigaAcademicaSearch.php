<?php

namespace app\modules\sisliga\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SisligaLigaAcademicaSearch represents the model behind the search form of `app\modules\sisliga\models\SisligaLigaAcademica`.
 */
class SisligaLigaAcademicaSearch extends SisligaLigaAcademica
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_liga_academica', 'id_pessoa', 'id_setor', 'situacao', 'sessao_congregacao', 'tipo_sessao_congregacao', ], 'integer'],
            [['nome', 'area_conhecimento', 'local_atuacao', 'resumo', 'data_aprovacao_comissao', 'data_homologacao_congregacao'], 'safe'],
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
     * A lista retornada por este método, quando a situação não é especificada, não retorna os
     * importados e os que aguardam definição de núcleo. Para poder exibir esses ligas na
     * lista de meus programas, foi adicionado o parâmetro $meusprogramas. Assim, a lista
     * pessoal de cada usuário estará completa, sem o filtro dos importados e aguardando núcleo.
     *
     * @param array $params
     * @param bool $meusprogramas
     *
     *
     * @return ActiveDataProvider
     */
    public function search($params, $meusprogramas = false)
    {
        $query = SisligaLigaAcademica::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id_liga_academica' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //Comentado em 20/09/2021 por causa da remoção da tramitação
        //if(!$meusprogramas && $this->situacao == null)
        //    $query->andWhere(['<','situacao',15]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id_pessoa' => $this->id_pessoa,
            'id_setor' => $this->id_setor,
            'situacao' => $this->situacao,
            'data_aprovacao_copex' => $this->data_aprovacao_comissao,
            'data_homologacao_congregacao' => $this->data_homologacao_congregacao,
            'sessao_congregacao' => $this->sessao_congregacao,
            'tipo_sessao_congregacao' => $this->tipo_sessao_congregacao,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome]);

        return $dataProvider;
    }

    /**
     * Método utilizado pelas telas index e _form de sisligarelatorio para retornar a lista
     * contendo id_liga_academica e nome de ligas homologados pela congragação e
     * ligas homologados com relatórios anteriores já enviados. O nome é limitado ao número
     * de caracteres especificado por $tamanho_nome. Caso $tamanho_nome seja 0, todo o nome
     * é retornado.
     * @param $tamanho_nome
     */
    public function listaLigas($tamanho_nome = 0)
    {
        $ligas = SisligaLigaAcademica::find()->where(['id_pessoa' => $this->id_pessoa, 'situacao' => [7,9]])
            ->select(['id_liga_academica','nome'])->asArray()->all();
            
        if($tamanho_nome > 0){
            $saida = [];
            foreach($ligas as $liga){
                $liga['nome'] = strlen($liga['nome']) > $tamanho_nome ?
                    substr($liga['nome'],0,$tamanho_nome-3).'...' : $liga['nome'];
                $saida[] = $liga;
            }
            return $saida;
        }

        return $ligas;
    }
}
