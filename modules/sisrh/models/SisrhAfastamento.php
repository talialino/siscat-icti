<?php

namespace app\modules\sisrh\models;

use Yii;

/**
 * This is the model class for table "sisrh_afastamento".
 *
 * @property int $id_afastamento
 * @property int $id_pessoa
 * @property string $inicio
 * @property string $termino
 * @property int $id_ocorrencia
 * @property string $observacao
 *
 * @property SisrhPessoa $pessoa
 * @property SisrhOcorrencia $ocorrencia
 */
class SisrhAfastamento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisrh_afastamento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pessoa', 'inicio', 'id_ocorrencia'], 'required'],
            [['id_pessoa', 'id_ocorrencia'], 'integer'],
            [['inicio', 'termino'], 'safe'],
            [['observacao'], 'string', 'max' => 255],
            [['id_pessoa'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhPessoa::className(), 'targetAttribute' => ['id_pessoa' => 'id_pessoa']],
            [['id_ocorrencia'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhOcorrencia::className(), 'targetAttribute' => ['id_ocorrencia' => 'id_ocorrencia']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_afastamento' => Yii::t('app', 'Leave'),
            'id_pessoa' => Yii::t('app', 'Person'),
            'inicio' => Yii::t('app', 'Begin'),
            'termino' => Yii::t('app', 'End'),
            'id_ocorrencia' => Yii::t('app', 'Occurrence'),
            'observacao' => 'Observação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoa()
    {
        return $this->hasOne(SisrhPessoa::className(), ['id_pessoa' => 'id_pessoa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCargo()
    {
        return $this->hasOne(SisrhCargo::className(), ['id_cargo' => 'id_cargo'])->via('pessoa');
    }

    public function getCategoria()
    {
        return $this->hasOne(SisrhCategoria::className(), ['id_categoria' => 'id_categoria'])->via('cargo');
    }

    public function getSetor()
    {
        return $this->hasOne(SisrhSetorPessoa::className(), ['id_pessoa' => 'id_pessoa'])->via('pessoa');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOcorrencia()
    {
        return $this->hasOne(SisrhOcorrencia::className(), ['id_ocorrencia' => 'id_ocorrencia']);
    }
}
