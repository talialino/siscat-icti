<?php

namespace app\modules\sisrh\models;

use Yii;

/**
 * This is the model class for table "sisrh_comissao".
 *
 * @property int $id_comissao
 * @property string $nome
 * @property string $sigla
 * @property string $data_inicio
 * @property string $data_fim
 * @property string $observacao
 * @property bool $eh_comissao_pit_rit
 * @property bool $eh_comissao_liga
 *
 * @property SisrhComissaoPessoa[] $sisrhComissaoPessoas
 * @property SisrhPessoa[] $pessoas
 * @property SisrhHistoricoFuncional[] $sisrhHistoricoFuncionais
 */
class SisrhComissao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisrh_comissao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['data_inicio', 'data_fim'], 'safe'],
            [['observacao'], 'string'],
            [['nome'], 'string', 'max' => 100],
            [['sigla'], 'string', 'max' => 20],
            [['eh_comissao_pit_rit', 'eh_comissao_liga'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_comissao' => 'Comissão',
            'nome' => 'Nome',
            'sigla' => 'Sigla',
            'data_inicio' => 'Data Início',
            'data_fim' => 'Data Fim',
            'observacao' => 'Observações',
            'eh_comissao_pit_rit' => 'Comissão de Avaliação PIT/RIT',
            'eh_comissao_liga' => 'Comissão de Liga Acadêmica',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisrhComissaoPessoas()
    {
        return $this->hasMany(SisrhComissaoPessoa::class, ['id_comissao' => 'id_comissao']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoas()
    {
        return $this->hasMany(SisrhPessoa::class, ['id_pessoa' => 'id_pessoa'])->viaTable('sisrh_comissao_pessoa', ['id_comissao' => 'id_comissao']);
    }
}
