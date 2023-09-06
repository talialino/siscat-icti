<?php

namespace app\modules\sisape\models;

use Yii;

/**
 * This is the model class for table "sisape_integrante_externo".
 *
 * @property int $id_integrante_externo
 * @property string $nome
 * @property string $email
 * @property string $telefone
 *
 * @property SisapeProjetoIntegrante[] $sisapeProjetoIntegrantes
 */
class SisapeIntegranteExterno extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisape_integrante_externo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['nome'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 100],
            [['telefone'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nome' => 'Nome',
            'email' => 'E-mail',
            'telefone' => 'Telefone',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisapeProjetoIntegrantes()
    {
        return $this->hasMany(SisapeProjetoIntegrante::className(), ['id_integrante_externo' => 'id_integrante_externo']);
    }
}
