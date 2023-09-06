<?php

namespace app\modules\siscc\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\siscc\models\SisccProgramaComponenteCurricular;

/**
 * SisccProgramaComponenteCurricularSearch represents the model behind the search form of `app\modules\siscc\models\SisccProgramaComponenteCurricular`.
 */
class SisccProgramaComponenteCurricularSearch extends SisccProgramaComponenteCurricular
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_programa_componente_curricular', 'id_componente_curricular', 'id_setor', 'id_semestre', 'situacao'], 'integer'],
            [['objetivo_geral', 'objetivos_especificos', 'conteudo_programatico', 'data_aprovacao_colegiado', 'data_aprovacao_coordenacao'], 'safe'],
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
    public function search($params, $sort = false)
    {        
        $query = SisccProgramaComponenteCurricular::find();

        $query->joinWith('componenteCurricular as componenteCurricular');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if($this->id_semestre == null)
            return null;

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_setor' => $this->id_setor,
            'id_semestre' => $this->id_semestre,
            'siscc_programa_componente_curricular.id_componente_curricular' => $this->id_componente_curricular,
            // 'data_aprovacao_colegiado' => $this->data_aprovacao_colegiado,
            // 'data_aprovacao_coordenacao' => $this->data_aprovacao_coordenacao,
             'situacao' => $this->situacao,
        ]);

        $query->joinWith('componenteCurricular as componenteCurricular');
        
        if(!$sort)
            $query->orderby('componenteCurricular.nome ASC');

        //As instruções a seguir servem para incluir os programas de componentes curriculares anuais
        //adicionados no semestre anterior
        $semestre = SisccSemestre::findOne($this->id_semestre);

        if(($semestre->ano - ($semestre->semestre % 2) >= 2020) && $semestre->semestre != 3){ //Componentes anuais são ignorados em semestres suplementares

            $semestreAnterior = SisccSemestre::find()->where([
                'ano' => $semestre->ano - ($semestre->semestre % 2),
                'semestre' => $semestre->semestre == 1 ? 2 : 1,
                ])->one();

            if($semestreAnterior != null){
                $query2 = SisccProgramaComponenteCurricular::find();
                $query2->innerJoinWith('componenteCurricular as componente');
                $query2->andFilterWhere([
                    'id_setor' => $this->id_setor,
                    'id_semestre' => $semestreAnterior->id_semestre,
                    'siscc_programa_componente_curricular.id_componente_curricular' => $this->id_componente_curricular,
                    'componente.anual' => 1,
                ]);
                $query->union($query2);
            }
        }

        return $dataProvider;
    }
}
