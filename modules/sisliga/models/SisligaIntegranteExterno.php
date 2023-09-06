<?php

namespace app\modules\sisliga\models;

use Yii;

/**
 * This is the model class for table "sisliga_integrante_externo".
 *
 * @property int $id_integrante_externo
 * @property string $nome
 * @property string $instituicao
 * @property string $telefone
 * @property string $email
 *
 * @property SisligaLigaIntegrante[] $sisligaLigaIntegrantes
 */
class SisligaIntegranteExterno extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisliga_integrante_externo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'instituicao'], 'required'],
            [['nome', 'instituicao'], 'string', 'max' => 255],
            [['telefone'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_integrante_externo' => 'Id Integrante Externo',
            'nome' => 'Nome',
            'instituicao' => 'Instituição',
            'telefone' => 'Telefone',
            'email' => 'Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisligaLigaIntegrantes()
    {
        return $this->hasMany(SisligaLigaIntegrante::class, ['id_integrante_externo' => 'id_integrante_externo']);
    }
}
