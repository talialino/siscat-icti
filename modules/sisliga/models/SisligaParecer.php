<?php

namespace app\modules\sisliga\models;

use Yii;
use app\modules\sisrh\models\SisrhPessoa;

/**
 * This is the model class for table "sisliga_parecer".
 *
 * @property int $id_parecer
 * @property int $id_liga_academica
 * @property int $id_relatorio
 * @property int $id_pessoa
 * @property string $parecer
 * @property string $data
 * @property int $atual
 *
 * @property SisligaLigaAcademica $ligaAcademica
 * @property SisligaRelatorio $relatorio
 * @property SisrhPessoa $pessoa
 */
class SisligaParecer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisliga_parecer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_liga_academica', 'id_relatorio', 'id_pessoa', 'atual'], 'integer'],
            [['id_pessoa'], 'required'],
            [['parecer'], 'string'],
            [['data'], 'safe'],
            [['id_liga_academica'], 'exist', 'skipOnError' => true, 'targetClass' => SisligaLigaAcademica::class, 'targetAttribute' => ['id_liga_academica' => 'id_liga_academica']],
            [['id_relatorio'], 'exist', 'skipOnError' => true, 'targetClass' => SisligaRelatorio::class, 'targetAttribute' => ['id_relatorio' => 'id_relatorio']],
            [['id_pessoa'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhPessoa::class, 'targetAttribute' => ['id_pessoa' => 'id_pessoa']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_parecer' => 'Parecer',
            'id_liga_academica' => 'Liga Acadêmica',
            'id_relatorio' => 'Relatório',
            'documentoTitulo' => 'Documento',
            'id_pessoa' => 'Parecerista',
            'nomeParecerista' => 'Parecerista',
            'parecer' => 'Parecer',
            'data' => 'Data',
            'atual' => 'Atual',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLigaAcademica()
    {
        return $this->hasOne(SisligaLigaAcademica::class, ['id_liga_academica' => 'id_liga_academica']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatorio()
    {
        return $this->hasOne(SisligaRelatorio::class, ['id_relatorio' => 'id_relatorio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoa()
    {
        return $this->hasOne(SisrhPessoa::class, ['id_pessoa' => 'id_pessoa']);
    }

    
    public function getDocumentoTitulo()
    {
        return $this->documento->nome;
    }

    public function getDocumento()
    {
        return $this->id_liga_academica != null ? $this->getLigaAcademica() : $this->getRelatorio();
    }

    public function getNomeParecerista()
    {
        return $this->pessoa->nome;
    }

    public function isEditable(){
        if(!$this->atual || $this->id_pessoa != Yii::$app->session->get('siscat_pessoa')->id_pessoa)
            return false;

        if($this->documento->situacao < 2 || $this->documento->situacao > 5)
            return false;

        return true;
    }
}
