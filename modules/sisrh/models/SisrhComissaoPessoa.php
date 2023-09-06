<?php

namespace app\modules\sisrh\models;

use Yii;

/**
 * This is the model class for table "sisrh_comissao_pessoa".
 *
 * @property int $id_comissao
 * @property int $id_pessoa
 * @property int $funcao
 *
 * @property SisrhComissao $comissao
 * @property SisrhPessoa $pessoa
 */
class SisrhComissaoPessoa extends \yii\db\ActiveRecord
{
    public const FUNCOES = [
        0 => 'Presidente',
        1 => 'Vice-presidente',
        2 => 'Membro',
        3 => 'Representante da direção',
        4 => 'Chefe Imediato',
        5 => 'Represetante dos servidores técnico-administrativos',
        6 => 'Secretária(o)'
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisrh_comissao_pessoa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_comissao', 'id_pessoa'], 'required'],
            [['id_comissao', 'id_pessoa', 'funcao'], 'integer'],
            [['id_comissao', 'id_pessoa'], 'unique', 'targetAttribute' => ['id_comissao', 'id_pessoa']],
            [['id_comissao'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhComissao::class, 'targetAttribute' => ['id_comissao' => 'id_comissao']],
            [['id_pessoa'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhPessoa::class, 'targetAttribute' => ['id_pessoa' => 'id_pessoa']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_comissao' => 'Comissão',
            'id_pessoa' => 'Pessoa',
            'funcao' => 'Função',
            'descricaoFuncao' => 'Função',
            'comissao.nome' => 'Comissão',
            'pessoa.nome' => 'Servidor',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComissao()
    {
        return $this->hasOne(SisrhComissao::class, ['id_comissao' => 'id_comissao']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoa()
    {
        return $this->hasOne(SisrhPessoa::class, ['id_pessoa' => 'id_pessoa']);
    }

    public function getDescricaoFuncao()
    {
        if($this->funcao === null)
            return null;
        return $this::FUNCOES[$this->funcao];
    }
}
