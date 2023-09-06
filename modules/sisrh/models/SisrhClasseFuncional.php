<?php

namespace app\modules\sisrh\models;

use Yii;

/**
 * This is the model class for table "sisrh_classe_funcional".
 *
 * @property int $id_classe_funcional
 * @property string $denominacao
 * @property int $id_categoria
 *
 * @property SisrhCategoria $categoria
 * @property SisrhPessoa[] $sisrhPessoas
 */
class SisrhClasseFuncional extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisrh_classe_funcional';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['denominacao', 'id_categoria'], 'required'],
            [['id_categoria'], 'integer'],
            [['denominacao'], 'string', 'max' => 20],
            [['id_categoria'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhCategoria::className(), 'targetAttribute' => ['id_categoria' => 'id_categoria']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_classe_funcional' => 'Id Classe Funcional',
            'denominacao' => Yii::t('app','Functional Class'),
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
        return $this->hasMany(SisrhPessoa::className(), ['id_classe_funcional' => 'id_classe_funcional']);
    }
}
