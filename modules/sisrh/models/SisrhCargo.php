<?php

namespace app\modules\sisrh\models;

use Yii;

/**
 * This is the model class for table "sisrh_cargo".
 *
 * @property int $id_cargo
 * @property string $descricao
 * @property int $id_categoria
 *
 * @property SisrhCategoria $categoria
 * @property SisrhPessoa[] $sisrhPessoas
 */
class SisrhCargo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisrh_cargo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descricao', 'id_categoria'], 'required'],
            [['id_categoria'], 'integer'],
            [['descricao'], 'string', 'max' => 255],
            [['atribuicoes'], 'string'],
            [['id_categoria'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhCategoria::className(), 'targetAttribute' => ['id_categoria' => 'id_categoria']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'descricao' => Yii::t('app','Office'),
            'atribuicoes' => 'Atribuições',
            'id_categoria' => Yii::t('app','Category'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(SisrhCategoria::className(), ['id_categoria' => 'id_categoria']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisrhPessoas()
    {
        return $this->hasMany(SisrhPessoa::className(), ['id_cargo' => 'id_cargo']);
    }
}
