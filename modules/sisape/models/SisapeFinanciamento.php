<?php

namespace app\modules\sisape\models;

use Yii;

/**
 * This is the model class for table "sisape_financiamento".
 *
 * @property int $id_financiamento
 * @property int $id_projeto
 * @property string $origem
 * @property string $valor
 *
 * @property SisapeProjeto $projeto
 */
class SisapeFinanciamento extends \yii\db\ActiveRecord
{
    public const ORIGENS = ['FAPESB','CNPQ','FINEP','UFBA','RECURSOS PRÓPRIOS'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisape_financiamento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_projeto'], 'required'],
            [['id_projeto'], 'integer'],
            [['valor'], 'number'],
            [['origem'], 'string', 'max' => 100],
            [['id_projeto'], 'exist', 'skipOnError' => true, 'targetClass' => SisapeProjeto::className(), 'targetAttribute' => ['id_projeto' => 'id_projeto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'origem' => 'Origem dos recursos financeiros',
            'valor' => 'Valor',
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
