<?php

namespace app\modules\sisrh\models;

use Yii;

/**
 * This is the model class for table "sisrh_municipio".
 *
 * @property int $id_municipio
 * @property string $nome
 * @property int $id_estado
 *
 * @property SisrhEstado $estado
 */
class SisrhMunicipio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisrh_municipio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'id_estado'], 'required'],
            [['id_estado'], 'integer'],
            [['nome'], 'string', 'max' => 50],
            [['id_estado'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhEstado::className(), 'targetAttribute' => ['id_estado' => 'id_estado']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_municipio' => 'Municipio',
            'nome' => Yii::t('app','City'),
            'id_estado' => Yii::t('app','State'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstado()
    {
        return $this->hasOne(SisrhEstado::className(), ['id_estado' => 'id_estado']);
    }
}
