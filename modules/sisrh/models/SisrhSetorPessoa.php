<?php

namespace app\modules\sisrh\models;

use Yii;

/**
 * This is the model class for table "sisrh_setor_pessoa".
 *
 * @property int $id_setor
 * @property int $id_pessoa
 * @property int $funcao
 *
 * @property SisrhPessoa $pessoa
 * @property SisrhSetor $setor
 */
class SisrhSetorPessoa extends \yii\db\ActiveRecord
{
    /**
     * Os índices dos elementos do array são potências de 2, pois @property app\models\AuthItemSisrhSetor $funcoes utiliza
     * um somatório daqueles para definir as funções que estão associadas a um papel ou permissão.
     */
    public $membroAutomatico = false;

    public const FUNCOES = [
        8 => 'Presidente',
        9 => 'Diretor',
        10 => 'Vice-diretor',
        0 => 'Coordenação',
        1 => 'Vice-coordenação',
        2 => 'Secretário(a)',
        3 => 'Membro',
        4 => 'Membro Titular',
        5 => 'Membro Suplente',
        6 => 'Representante Técnico Titular',
        7 => 'Representante Técnico Suplente',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisrh_setor_pessoa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_setor', 'id_pessoa', 'funcao'], 'required'],
            [['id_setor', 'id_pessoa', 'funcao'], 'integer'],
            [['id_pessoa'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhPessoa::className(), 'targetAttribute' => ['id_pessoa' => 'id_pessoa']],
            [['id_setor'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhSetor::className(), 'targetAttribute' => ['id_setor' => 'id_setor']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_setor' => Yii::t('app','Sector'),
            'id_pessoa' => Yii::t('app','Person'),
            'funcao' => Yii::t('app','Function'),
            'descricaoFuncao' => 'Função',
            'pessoa.nome' => 'Servidor',
            'setor.nome' => 'Setor',
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
    public function getSetor()
    {
        return $this->hasOne(SisrhSetor::className(), ['id_setor' => 'id_setor']);
    }

    public function getDescricaoFuncao()
    {
        if($this->funcao === null)
            return null;
        return $this::FUNCOES[$this->funcao];
    }
}
