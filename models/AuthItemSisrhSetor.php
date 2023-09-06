<?php

namespace app\models;

use app\modules\sisrh\models\SisrhComissao;
use app\modules\sisrh\models\SisrhComissaoPessoa;
use Yii;
use app\modules\sisrh\models\SisrhSetor;
use app\modules\sisrh\models\SisrhSetorPessoa;

//use dektrium\rbac\models\AuthItem;

/**
 * This is the model class for table "auth_item_sisrh_setor".
 * 
 * A relação da @property string $name com dektrium\rbac\models\AuthItem foi removida desta classe, pois AuthItem não estende ActiveRecord,
 * o que estava gerando conflitos no processamento dessa relação. O código referente a esta relaciona se encontra comentado apenas para
 * relembrar essa ligação.
 *
 * @property int $id
 * @property string $name
 * @property int $id_setor
 * @property int $funcao
 * @property int $id_comissao
 *
 * @property SisrhSetor $setor
 */
class AuthItemSisrhSetor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item_sisrh_setor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['id', 'id_setor', 'id_comissao', 'funcao'], 'integer'],
            [['name'], 'string', 'max' => 64],
           // [['name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::class, 'targetAttribute' => ['name' => 'name']],
            [['id_setor'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhSetor::class, 'targetAttribute' => ['id_setor' => 'id_setor']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Papel',
            'id_setor' => 'Setor',
            'setor.nome' => 'Setor',
            'id_comissao' => 'Comissão',
            'comissao.nome' => 'Comissão',
            'setorComissao.nome' => 'Setor/Comissão',
            'funcao' => 'Função',
            'funcaoDescricao' => 'Função',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
   /* public function getName0()
    {
        return $this->hasOne(AuthItem::class, ['name' => 'name']);
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSetor()
    {
        return $this->hasOne(SisrhSetor::class, ['id_setor' => 'id_setor']);
    }

    public function getComissao()
    {
        return $this->hasOne(SisrhComissao::class, ['id_comissao' => 'id_comissao']);
    }

    public function getFuncaoDescricao()
    {
        return $this->id_setor ? SisrhSetorPessoa::FUNCOES[$this->funcao] : SisrhComissaoPessoa::FUNCOES[$this->funcao];
    }

    public function getSetorComissao()
    {
        return $this->id_setor ? $this->getSetor() : $this->getComissao();
    }
}
