<?php

namespace app\modules\sisrh\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use app\modules\sisrh\models\SisrhSetorPessoa;
use app\modules\sisrh\models\SisrhSetorMembroAutomatico;

/**
 * SisrhSetorPessoaSearch represents the model behind the search form of `app\modules\sisrh\models\SisrhSetorPessoa`.
 */
class SisrhSetorPessoaSearch extends SisrhSetorPessoa
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_setor', 'id_pessoa', 'funcao'], 'integer'],
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
    public function search($id_setor)
    {
        $query = SisrhSetorPessoa::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        //$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->innerJoinWith('pessoa as pessoa');

        // grid filtering conditions
        $query->andFilterWhere([
            'id_setor' => $id_setor,
            'sisrh_setor_pessoa.id_pessoa' => $this->id_pessoa,
            'pessoa.situacao' => 1,
            'funcao' => $this->funcao,
        ]);

        $query->orderby('funcao');

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ArrayDataProvider
     */
    public function searchArray($id_setor)
    {
        $query = SisrhSetorPessoa::find();

        // add conditions that should always apply here

        //$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->innerJoinWith('pessoa as pessoa');

        // grid filtering conditions
        $query->andFilterWhere([
            'id_setor' => $id_setor,
            'pessoa.situacao' => 1,
            'sisrh_setor_pessoa.id_pessoa' => $this->id_pessoa,
            'funcao' => $this->funcao,
        ]);

        $query->orderby('funcao'); 

        $array = $query->all();

        $membros = SisrhSetorMembroAutomatico::find()->where(['id_setor_destino' => $id_setor])->all();

        foreach($membros as $membro){
            $setorpessoas = SisrhSetorPessoa::find()->innerJoinWith('pessoa as pessoa')
                ->select(['sisrh_setor_pessoa.id_pessoa'])->where([
                'id_setor' => $membro->id_setor_origem,
                'pessoa.situacao' => 1,
                'funcao' => $membro->funcao_origem])->all();
            foreach($setorpessoas as $setorpessoa){
                $setorpessoa->id_setor = $membro->id_setor_destino;
                $setorpessoa->funcao = $membro->funcao_destino;
                $setorpessoa->membroAutomatico = true;
                $array[] = $setorpessoa;
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $array,
            'pagination' => false,
            'sort' => [
                'attributes' => ['descricaoFuncao','pessoa.nome'],
            ],
        ]);

        return $dataProvider;
    }
}
