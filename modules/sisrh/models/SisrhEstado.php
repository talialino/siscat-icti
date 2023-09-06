<?php

namespace app\modules\sisrh\models;

use Yii;

/**
 * This is the model class for table "sisrh_estado".
 *
 * @property int $id_estado
 * @property string $sigla
 * @property string $nome
 *
 * @property SisrhMunicipio[] $sisrhMunicipios
 */
class SisrhEstado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisrh_estado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sigla', 'nome'], 'required'],
            [['sigla'], 'string', 'max' => 2],
            [['nome'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_estado' => 'Estado',
            'sigla' => Yii::t('app','Initials'),
            'nome' => Yii::t('app','State'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisrhMunicipios()
    {
        return $this->hasMany(SisrhMunicipio::className(), ['id_estado' => 'id_estado']);
    }
}
