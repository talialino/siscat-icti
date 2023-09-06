<?php

namespace app\modules\sisext\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\sisape\models\SisapeProjeto;

/**
 * Modelo para armazenar os campos dos formulários de pesquisa da tela lista de projetos
 */
class SisapeForm extends \yii\base\Model
{
    public $tipo_projeto;
    public $id_pessoa;
    public $ano;
    public $titulo;
    public $area_atuacao;

    public function attributeLabels()
    {
        return [
            'tipo_projeto' => 'Tipo de projeto',
            'id_pessoa' => 'Coordenador',
            'ano' => 'Projetos ativos em (ano)',
            'titulo' => 'Título',
            'area_atuacao' => 'Área do conhecimento',
        ];
    }

    public function rules()
    {
        return [
            [['tipo_projeto','ano','id_pessoa'], 'integer'],
            [['area_atuacao', 'titulo'], 'string'],
        ];
    }

    public function search($params)
    {
        return $this->pesquisarProjetos($params);
    }

    public function pesquisarProjetos($params)
    {
        if(isset($params['SisapeForm'])){
            $this->tipo_projeto = $params['SisapeForm']['tipo_projeto'];
            $this->id_pessoa = $params['SisapeForm']['id_pessoa'];
            $this->ano = $params['SisapeForm']['ano'];
            $this->titulo = $params['SisapeForm']['titulo'];
            $this->area_atuacao = $params['SisapeForm']['area_atuacao'];
        }
        $query = SisapeProjeto::find();
        $query->andFilterWhere([
            'tipo_projeto' => $this->tipo_projeto,
            'id_pessoa' => $this->id_pessoa,
        ]);

        $query->andFilterWhere(['like', 'area_atuacao', $this->area_atuacao]);
        $query->andFilterWhere(['like', 'titulo', $this->titulo]);
        $query->andWhere(['situacao' => [12,14]]);

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
            'sort' => [
                'defaultOrder' => [
                    'data_inicio' => SORT_DESC,
                ]
            ],
        ]);

        return $dataProvider;
    }
}