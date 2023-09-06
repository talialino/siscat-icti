<?php

namespace app\modules\sisrh\models;

use Yii;

/**
 * This is the model class for table "sisrh_setor_membro_automatico".
 *
 * @property int $id_membro_automatico
 * @property int $id_setor_origem
 * @property int $funcao_origem
 * @property int $id_setor_destino
 * @property int $funcao_destino
 *
 * @property SisrhSetor $setorOrigem
 * @property SisrhSetor $setorDestino
 */
class SisrhSetorMembroAutomatico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisrh_setor_membro_automatico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_setor_origem', 'funcao_origem', 'id_setor_destino', 'funcao_destino'], 'required'],
            [['id_membro_automatico', 'id_setor_origem', 'funcao_origem', 'id_setor_destino', 'funcao_destino'], 'integer'],
            [['id_membro_automatico'], 'unique'],
            [['id_setor_origem'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhSetor::className(), 'targetAttribute' => ['id_setor_origem' => 'id_setor']],
            [['id_setor_destino'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhSetor::className(), 'targetAttribute' => ['id_setor_destino' => 'id_setor']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_setor_origem' => 'Setor de Origem',
            'setorOrigem.nome' => 'Setor de Origem',
            'funcao_origem' => 'Função de Origem',
            'funcaoOrigem' => 'Função de Origem',
            'id_setor_destino' => 'Setor de Destino',
            'setorDestino.nome' => 'Setor de Destino',
            'funcao_destino' => 'Função de Destino',
            'funcaoDestino' => 'Função de Destino',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSetorOrigem()
    {
        return $this->hasOne(SisrhSetor::className(), ['id_setor' => 'id_setor_origem']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSetorDestino()
    {
        return $this->hasOne(SisrhSetor::className(), ['id_setor' => 'id_setor_destino']);
    }

    public function getFuncaoOrigem()
    {
        return SisrhSetorPessoa::FUNCOES[$this->funcao_origem];
    }

    public function getFuncaoDestino()
    {
        return SisrhSetorPessoa::FUNCOES[$this->funcao_destino];
    }
}
