<?php

namespace app\modules\sisape\models;

use Yii;
use app\modules\sisrh\models\SisrhPessoa;

/**
 * This is the model class for table "sisape_parecer".
 *
 * @property int $id_parecer
 * @property int $id_projeto
 * @property int $id_relatorio
 * @property int $id_pessoa
 * @property int $tipo_parecerista
 * @property string $parecer
 * @property string $data
 * @property int $atual
 *
 * @property SisapeProjeto $projeto
 * @property SisapeRelatorio $relatorio
 * @property SisrhPessoa $pessoa
 */
class SisapeParecer extends \yii\db\ActiveRecord
{
    //Tipo de parecerista do núcleo acadêmico
    public const PARECERISTA_NUCLEO = 1;

    //tipo de parecerista da coordenação acadêmica de pesquisa e extensão
    public const PARECERISTA_COPEX = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisape_parecer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pessoa'], 'required'],
            [['id_projeto', 'id_relatorio', 'id_pessoa', 'tipo_parecerista', 'atual'], 'integer'],
            [['parecer'], 'string'],
            [['data'], 'safe'],
            [['id_projeto'], 'exist', 'skipOnError' => true, 'targetClass' => SisapeProjeto::className(), 'targetAttribute' => ['id_projeto' => 'id_projeto']],
            [['id_relatorio'], 'exist', 'skipOnError' => true, 'targetClass' => SisapeRelatorio::className(), 'targetAttribute' => ['id_relatorio' => 'id_relatorio']],
            [['id_pessoa'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhPessoa::className(), 'targetAttribute' => ['id_pessoa' => 'id_pessoa']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_parecer' => 'Parecer',
            'id_projeto' => 'Projeto',
            'id_relatorio' => 'Relatório',
            'documentoTitulo' => 'Documento',
            'id_pessoa' => 'Parecerista',
            'nomeParecerista' => 'Parecerista',
            'tipo_parecerista' => 'Tipo de Parecerista',
            'tipo' => 'Tipo de Parecerista',
            'parecer' => 'Parecer',
            'data' => 'Data',
            'atual' => 'Atual',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjeto()
    {
        return $this->hasOne(SisapeProjeto::className(), ['id_projeto' => 'id_projeto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatorio()
    {
        return $this->hasOne(SisapeRelatorio::className(), ['id_relatorio' => 'id_relatorio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoa()
    {
        return $this->hasOne(SisrhPessoa::className(), ['id_pessoa' => 'id_pessoa']);
    }

    public function getDocumentoTitulo()
    {
        return $this->getDocumento()->titulo;
    }

    public function getDocumento()
    {
        return $this->id_projeto != null ? $this->projeto : $this->relatorio;
    }

    public function getNomeParecerista()
    {
        return $this->pessoa->nome;
    }

    public function getTipo(){
        switch($this->tipo_parecerista){
            case self::PARECERISTA_NUCLEO:
                return 'Núcleo';
            case self::PARECERISTA_COPEX:
                return 'COPEX';
        }
    }

    public function isEditable(){
        if(!$this->atual || $this->id_pessoa != Yii::$app->session->get('siscat_pessoa')->id_pessoa)
            return false;

        switch($this->getDocumento()->situacao){
            case 2:case 3:case 4:case 5:
                if($this->tipo_parecerista != self::PARECERISTA_NUCLEO)
                    return false;
            break;
            case 7:case 8:case 9:case 10:
                if($this->tipo_parecerista != self::PARECERISTA_COPEX)
                    return false;
            break;
            default:
                return false;
        }
        return true;
    }
}
