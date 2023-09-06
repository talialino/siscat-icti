<?php

namespace app\modules\sispit\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sispit\models\SispitParecer;

/**
 * SispitParecerSearch represents the model behind the search form of `app\modules\sispit\models\SispitParecer`.
 */
class SispitParecerSearch extends SispitParecer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_parecer', 'id_plano_relatorio', 'id_pessoa', 'tipo_parecerista', 'atual', 'pit_rit'], 'integer'],
            [['parecer', 'data'], 'safe'],
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
        $query = SispitParecer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false,
        ]);

        $query->orderBy('id_parecer DESC');

        $this->attributes = $params;

        if($this->id_plano_relatorio === null){
            // grid filtering conditions
            $query->andFilterWhere([
                'id_pessoa' => $this->id_pessoa,
            ]);
            
            $query->joinWith('planoRelatorio AS planoRelatorio');
            $query->andWhere(['planoRelatorio.id_ano' => $params['id_ano']]);
        }
        else{
            $query->andFilterWhere([
                'id_plano_relatorio' => $this->id_plano_relatorio,
                'pit_rit' => $this->pit_rit,
                'tipo_parecerista' => $this->tipo_parecerista,
            ]);
            $query->andWhere(['not',['parecer' => null]]);
        }

        return $dataProvider;
    }

    /**
     * Retorna o último parecer recebido pelo SispitPlanoRelatorio. Se $atual for 0, retorna o penúltimo.
     * @param int $id_plano_relatorio
     * @param int $atual
     * @return SispitParecer 
     */
    public static function ultimoParecer($id_plano_relatorio, $atual = 1)
    {
        return SispitParecer::find()->andFilterWhere(['id_plano_relatorio' => $id_plano_relatorio, 'atual' => $atual])->orderBy(['id_parecer' => SORT_DESC])->one();
    }
}
