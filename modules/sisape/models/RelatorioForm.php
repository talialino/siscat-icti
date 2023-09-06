<?php

namespace app\modules\sisape\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * Modelo para armazenar os campos dos formulários de pesquisa dos relatórios de sistema
 */
class RelatorioForm extends \yii\base\Model
{
    public const SCENARIO_PROJETOS = 'projetos';
    public const SCENARIO_PARTICIPANTES = 'participantes';

    public $tipo_projeto;
    public $situacao;
    public $ano;
    public $origem_financiamento;
    public $tipo_integrante;
    public $vinculo_integrante;
    public $area_atuacao;

    public function attributeLabels()
    {
        return [
            'tipo_projeto' => 'Tipo de projeto',
            'situacao' => 'Situação',
            'ano' => 'Projetos ativos em (ano)',
            'origem_financiamento' => 'Origem dos recursos financeiros',
            'tipo_integrante' => 'Tipo de integrante',
            'vinculo_integrante' => 'Vínculo',
            'area_atuacao' => 'Área do conhecimento',
        ];
    }

    public function rules()
    {
        return [
            [['tipo_projeto','situacao','ano','origem_financiamento', 'tipo_integrante','vinculo_integrante'], 'integer'],
            [['area_atuacao'], 'safe'],
        ];
    }

    public function search($params)
    {
        switch($this->getScenario()){
            case self::SCENARIO_PROJETOS:
                return $this->pesquisarProjetos($params);
            case self::SCENARIO_PARTICIPANTES:
                return $this->pesquisarParticipantes($params);
        }
    }

    public function pesquisarProjetos($params)
    {
        if(isset($params['RelatorioForm'])){
            $this->tipo_projeto = $params['RelatorioForm']['tipo_projeto'];
            $this->situacao = $params['RelatorioForm']['situacao'];
            $this->ano = $params['RelatorioForm']['ano'];
            $this->origem_financiamento = $params['RelatorioForm']['origem_financiamento'];
            $this->area_atuacao = $params['RelatorioForm']['area_atuacao'];
        }
        $query = SisapeProjeto::find();
        $query->andFilterWhere([
            'tipo_projeto' => $this->tipo_projeto,
            'situacao' => $this->situacao,
        ]);

        $query->andFilterWhere(['like', 'area_atuacao', $this->area_atuacao]);

        if($this->ano){
            $query->andWhere(
                ['or',
                    ['or',
                        ['and',
                            ['>=','data_inicio',"$this->ano-01-01"],
                            ['<=','data_inicio',"$this->ano-12-31"],
                        ],
                        ['and',
                            ['>=','data_fim',"$this->ano-01-01"],
                            ['<=','data_fim',"$this->ano-12-31"],
                        ]
                    ],
                    ['and',
                        ['<','data_inicio',"$this->ano-01-01"],
                        ['>','data_fim',"$this->ano-12-31"],
                    ]
                ]
            );
        }

        if(isset($params['RelatorioForm']['origem_financiamento']) && is_numeric($params['RelatorioForm']['origem_financiamento'])){
            $financiamento = SisapeFinanciamento::ORIGENS[$this->origem_financiamento];
            $query->joinWith('sisapeFinanciamentos as financiamento');
            $query->andWhere(['like','financiamento.origem',$financiamento]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50],
            'sort' => false,
        ]);

        return $dataProvider;
    }

    public function pesquisarParticipantes($params)
    {
        if(isset($params['RelatorioForm'])){
            $this->tipo_projeto = $params['RelatorioForm']['tipo_projeto'];
            $this->situacao = $params['RelatorioForm']['situacao'];
            $this->ano = $params['RelatorioForm']['ano'];
            $this->tipo_integrante = $params['RelatorioForm']['tipo_integrante'];
            $this->vinculo_integrante = $params['RelatorioForm']['vinculo_integrante'];
            $this->area_atuacao = $params['RelatorioForm']['area_atuacao'];
        }
        $query = SisapeProjetoIntegrante::find();
        $query->joinWith('projeto as projeto');

        $query->andFilterWhere([
            'projeto.tipo_projeto' => $this->tipo_projeto,
            'projeto.situacao' => $this->situacao,
            'vinculo' => $this->vinculo_integrante,
        ]);

        switch($this->tipo_integrante){
            case 1:
                $query->andWhere(['not',['id_aluno' => null]]);
            break;
            case 2:
                $query->andWhere(['not',['sisape_projeto_integrante.id_pessoa' => null]]);
            break;
            case 3:
                $query->andWhere(['not',['id_integrante_externo' => null]]);                
        }

        $query->andFilterWhere(['like', 'projeto.area_atuacao', $this->area_atuacao]);

        if($this->ano){
            $query->andWhere(
                ['or',
                    ['or',
                        ['and',
                            ['>=','projeto.data_inicio',"$this->ano-01-01"],
                            ['<=','projeto.data_inicio',"$this->ano-12-31"],
                        ],
                        ['and',
                            ['>=','projeto.data_fim',"$this->ano-01-01"],
                            ['<=','projeto.data_fim',"$this->ano-12-31"],
                        ]
                    ],
                    ['and',
                        ['<','projeto.data_inicio',"$this->ano-01-01"],
                        ['>','projeto.data_fim',"$this->ano-12-31"],
                    ]
                ]
            );
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50],
            'sort' => false,
        ]);

        return $dataProvider;
    }
}