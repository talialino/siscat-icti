<?php

namespace app\modules\sisliga\models;

use yii\data\ActiveDataProvider;

/**
 * Modelo para armazenar os campos dos formulários de pesquisa dos relatórios de sistema
 */
class RelatorioForm extends \yii\base\Model
{
    public const SCENARIO_LIGAS = 'ligas';
    public const SCENARIO_PARTICIPANTES = 'participantes';

    public $situacao;
    public $ano;
    public $tipo_integrante;
    public $area_conhecimento;

    public function attributeLabels()
    {
        return [
            'situacao' => 'Situação',
            'ano' => 'Ligas ativas em (ano)',
            'tipo_integrante' => 'Tipo de integrante',
            'area_conhecimento' => 'Área do conhecimento',
        ];
    }

    public function rules()
    {
        return [
            [['situacao','ano', 'tipo_integrante'], 'integer'],
            [['area_conhecimento'], 'safe'],
        ];
    }

    public function search($params)
    {
        switch($this->getScenario()){
            case self::SCENARIO_LIGAS:
                return $this->pesquisarLigasAcademicas($params);
            case self::SCENARIO_PARTICIPANTES:
                return $this->pesquisarParticipantes($params);
        }
    }

    public function pesquisarLigasAcademicas($params)
    {
        if(isset($params['RelatorioForm'])){
            $this->situacao = $params['RelatorioForm']['situacao'];
            $this->area_conhecimento = $params['RelatorioForm']['area_conhecimento'];
        }
        $query = SisligaLigaAcademica::find();
        $query->andFilterWhere([
            'situacao' => $this->situacao,
        ]);

        $query->andFilterWhere(['like', 'area_conhecimento', $this->area_conhecimento]);

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
            $this->situacao = $params['RelatorioForm']['situacao'];
            $this->tipo_integrante = $params['RelatorioForm']['tipo_integrante'];
            $this->area_conhecimento = $params['RelatorioForm']['area_conhecimento'];
        }
        $query = SisligaLigaIntegrante::find();
        $query->joinWith('ligaAcademica as ligaAcademica');

        $query->andFilterWhere([
            'ligaAcademica.situacao' => $this->situacao,
        ]);

        switch($this->tipo_integrante){
            case 1:
                $query->andWhere(['not',['id_aluno' => null]]);
            break;
            case 2:
                $query->andWhere(['not',['sisape_liga_integrante.id_pessoa' => null]]);
            break;
            case 3:
                $query->andWhere(['not',['id_integrante_externo' => null]]);                
        }

        $query->andFilterWhere(['like', 'liga.area_conhecimento', $this->area_conhecimento]);

        if($this->ano){
            $query->andWhere(
                ['or',
                    ['or',
                        ['and',
                            ['>=','liga.data_inicio',"$this->ano-01-01"],
                            ['<=','liga.data_inicio',"$this->ano-12-31"],
                        ],
                        ['and',
                            ['>=','liga.data_fim',"$this->ano-01-01"],
                            ['<=','liga.data_fim',"$this->ano-12-31"],
                        ]
                    ],
                    ['and',
                        ['<','liga.data_inicio',"$this->ano-01-01"],
                        ['>','liga.data_fim',"$this->ano-12-31"],
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