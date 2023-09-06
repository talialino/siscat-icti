<?php

namespace app\modules\sisape\models;

use Yii;

/**
 * This is the model class for table "sisape_atividade".
 *
 * @property int $id_atividade
 * @property int $id_projeto
 * @property string $data_inicio
 * @property string $data_fim
 * @property string $descricao_atividade
 * @property int $concluida
 *
 * @property SisapeProjeto $projeto
 */
class SisapeAtividade extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisape_atividade';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_projeto', 'descricao_atividade'], 'required'],
            [['id_projeto', 'concluida'], 'integer'],
            [['data_inicio', 'data_fim'], 'safe'],
            [['descricao_atividade'], 'string'],
            [['id_projeto'], 'exist', 'skipOnError' => true, 'targetClass' => SisapeProjeto::className(), 'targetAttribute' => ['id_projeto' => 'id_projeto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'data_inicio' => 'Data Início',
            'data_fim' => 'Data Fim',
            'descricao_atividade' => 'Descrição Atividade',
            'concluida' => 'Concluída',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjeto()
    {
        return $this->hasOne(SisapeProjeto::className(), ['id_projeto' => 'id_projeto']);
    }
}
