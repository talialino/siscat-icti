<?php

namespace app\modules\sispit\models;

use Yii;

/**
 * This is the model class for table "sispit_ano".
 *
 * @property int $ano
 *
 * @property SispitPlanoRelatorio[] $sispitPlanoRelatorios
 */
class SispitAno extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sispit_ano';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ano','ano', 'suplementar'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ano' => 'Ano',
            'suplementar' => 'Semestre Suplementar',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitPlanoRelatorios()
    {
        return $this->hasMany(SispitPlanoRelatorio::className(), ['ano' => 'ano']);
    }

    public function getString()
    {
        return ($this->suplementar ? 'SLS ' : '').$this->ano;
    }
}
