<?php

namespace app\modules\sisape\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SisapeProjetoSearch represents the model behind the search form of `app\modules\sisape\models\SisapeProjeto`.
 */
class SisapeProjetoSearch extends SisapeProjeto
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_projeto', 'id_pessoa', 'id_setor', 'tipo_projeto', 'submetido_etica', 'disponivel_site', 'situacao', 'sessao_congregacao', 'tipo_sessao_congregacao', 'tipo_extensao'], 'integer'],
            [['titulo', 'area_atuacao', 'local_execucao', 'parcerias', 'infraestrutura', 'resumo', 'introducao', 'justificativa', 'objetivos', 'metodologia', 'resultados_esperados', 'data_inicio', 'data_fim', 'orcamento', 'referencias', 'data_aprovacao_nucleo', 'data_aprovacao_copex', 'data_homologacao_congregacao'], 'safe'],
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
     * importados e os que aguardam definição de núcleo. Para poder exibir esses projetos na
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
        $query = SisapeProjeto::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_inicio' => SORT_DESC,
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
            'tipo_projeto' => $this->tipo_projeto,
            'situacao' => $this->situacao,
            'data_inicio' => $this->data_inicio,
            'data_fim' => $this->data_fim,
            'data_aprovacao_nucleo' => $this->data_aprovacao_nucleo,
            'data_aprovacao_copex' => $this->data_aprovacao_copex,
            'data_homologacao_congregacao' => $this->data_homologacao_congregacao,
            'sessao_congregacao' => $this->sessao_congregacao,
            'tipo_sessao_congregacao' => $this->tipo_sessao_congregacao,
            'tipo_extensao' => $this->tipo_extensao,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo]);

        return $dataProvider;
    }

    /**
     * Método utilizado pelas telas index e _form de sisaperelatorio para retornar a lista
     * contendo id_projeto e titulo de projetos homologados pela congragação e
     * projetos homologados com relatórios anteriores já enviados. O título é limitado ao número
     * de caracteres especificado por $tamanho_titulo. Caso $tamanho_titulo seja 0, todo o título
     * é retornado.
     * @param $tamanho_titulo
     */
    public function listaProjetos($tamanho_titulo = 0)
    {
        $projetos = SisapeProjeto::find()->where(['id_pessoa' => $this->id_pessoa, 'situacao' => [12,14]])
            ->select(['id_projeto','titulo'])->asArray()->all();
            
        if($tamanho_titulo > 0){
            $saida = [];
            foreach($projetos as $projeto){
                $projeto['titulo'] = strlen($projeto['titulo']) > $tamanho_titulo ?
                    substr($projeto['titulo'],0,$tamanho_titulo-3).'...' : $projeto['titulo'];
                $saida[] = $projeto;
            }
            return $saida;
        }

        return $projetos;
    }
}
