<?php

namespace app\modules\sisrh\models;

use Yii;

/**
 * This is the model class for table "sisrh_ocorrencia".
 *
 * @property int $id_ocorrencia
 * @property string $justificativa
 *
 * @property SisrhAfastamento[] $sisrhAfastamentos
 */
class SisrhOcorrencia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisrh_ocorrencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['justificativa'], 'required'],
            [['justificativa'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_ocorrencia' => Yii::t('app', 'Occurrence'),
            'justificativa' => Yii::t('app', 'Justification'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisrhAfastamentos()
    {
        return $this->hasMany(SisrhAfastamento::className(), ['justificativa' => 'id_ocorrencia']);
    }
}
