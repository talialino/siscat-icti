<?php

namespace app\modules\sisrh\models;

use Yii;

/**
 * This is the model class for table "sisrh_categoria".
 *
 * @property int $id_categoria
 * @property string $nome
 *
 * @property SisrhCargo[] $sisrhCargos
 * @property SisrhClasseFuncional[] $sisrhClasseFuncionals
 */
class SisrhCategoria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisrh_categoria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['nome'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_categoria' => 'Categoria',
            'nome' => Yii::t('app','Category'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisrhCargos()
    {
        return $this->hasMany(SisrhCargo::className(), ['id_categoria' => 'id_categoria']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisrhClasseFuncionals()
    {
        return $this->hasMany(SisrhClasseFuncional::className(), ['id_categoria' => 'id_categoria']);
    }
}
