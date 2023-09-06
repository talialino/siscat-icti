<?php

namespace app\modules\siscc\models;

use Yii;
use app\modules\sisrh\models\SisrhPessoa;

/**
 * This is the model class for table "siscc_parecer".
 *
 * @property int $id_parecer
 * @property int $id_programa_componente_curricular
 * @property int $id_pessoa
 * @property int $tipo_parecerista
 * @property string $parecer
 * @property string $data
 * @property int $atual
 * @property int $edicao
 *
 * @property SisccProgramaComponenteCurricular $programaComponenteCurricular
 * @property SisrhPessoa $pessoa
 */
class SisccParecer extends \yii\db\ActiveRecord
{
    //Tipo de parecerista do colegiado acadêmico
    public const PARECERISTA_COLEGIADO = 1;

    //tipo de parecerista da coordenação acadêmica de ensino
    public const PARECERISTA_CAC = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siscc_parecer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_programa_componente_curricular', 'id_pessoa'], 'required'],
            [['id_programa_componente_curricular', 'id_pessoa', 'tipo_parecerista', 'atual', 'edicao'], 'integer'],
            [['parecer', 'comentario'], 'string'],
            [['data'], 'safe'],
            [['id_programa_componente_curricular'], 'exist', 'skipOnError' => true, 'targetClass' => SisccProgramaComponenteCurricular::class, 'targetAttribute' => ['id_programa_componente_curricular' => 'id_programa_componente_curricular']],
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
            'id_programa_componente_curricular' => 'Programa Componente Curricular',
            'id_pessoa' => 'Parecerista',
            'nomeParecerista' => 'Parecerista',
            'tipo_parecerista' => 'Tipo de Parecerista',
            'tipo' => 'Tipo de Parecerista',
            'parecer' => 'Parecer',
            'data' => 'Data do parecer',
            'edicao' => 'Permissão para Editar Programa',
            'comentario' => 'Mensagem',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramaComponenteCurricular()
    {
        return $this->hasOne(SisccProgramaComponenteCurricular::class, ['id_programa_componente_curricular' => 'id_programa_componente_curricular']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoa()
    {
        return $this->hasOne(SisrhPessoa::class, ['id_pessoa' => 'id_pessoa']);
    }
    public function getNomeParecerista()
    {
        return $this->pessoa->nome;
    }

    public function getTipo(){
        switch($this->tipo_parecerista){
            case self::PARECERISTA_COLEGIADO:
                return 'Colegiado';
            case self::PARECERISTA_CAC:
                return 'CAC';
        }
    }

    public function isEditable(){
        if(!$this->atual || $this->id_pessoa != Yii::$app->session->get('siscat_pessoa')->id_pessoa)
            return false;
        switch($this->programaComponenteCurricular->situacao){
            case 2:case 3:case 4:case 5:
                if($this->tipo_parecerista != self::PARECERISTA_COLEGIADO)
                    return false;
            break;
            case 7:case 8:case 9:case 10:
                if($this->tipo_parecerista != self::PARECERISTA_CAC)
                    return false;
            break;
            default:
                return false;
        }
        return true;
    }
}
