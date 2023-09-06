<?php

namespace app\modules\sispit\models;

use Yii;
use app\modules\sisrh\models\SisrhPessoa;

/**
 * This is the model class for table "sispit_parecer".
 *
 * @property int $id_parecer
 * @property int $id_plano_relatorio
 * @property int $id_pessoa
 * @property int $tipo_parecerista
 * @property string $parecer
 * @property string $data
 * @property int $atual
 * @property int $pit_rit
 * @property int $comissao_pit_rit
 * @property string comentario
 *
 * @property SispitPlanoRelatorio $planoRelatorio
 * @property SisrhPessoa $pessoa
 */
class SispitParecer extends \yii\db\ActiveRecord
{
    //Tipo de parecerista do núcleo acadêmico
    public const PARECERISTA_NUCLEO = 1;

    //tipo de parecerista da coordenação acadêmica de ensino
    public const PARECERISTA_CAC = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sispit_parecer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plano_relatorio', 'id_pessoa', 'tipo_parecerista','pit_rit'], 'required'],
            [['id_plano_relatorio', 'id_pessoa', 'tipo_parecerista', 'atual', 'pit_rit', 'comissao_pit_rit'], 'integer'],
            [['parecer', 'comentario'], 'string'],
            [['data'], 'safe'],
            [['id_plano_relatorio'], 'exist', 'skipOnError' => true, 'targetClass' => SispitPlanoRelatorio::class, 'targetAttribute' => ['id_plano_relatorio' => 'id_plano_relatorio']],
            [['id_pessoa'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhPessoa::class, 'targetAttribute' => ['id_pessoa' => 'id_pessoa']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pessoa' => 'Parecerista',
            'nomeParecerista' => 'Parecerista',
            'tipo_parecerista' => 'Tipo de Parecerista',
            'tipo' => 'Tipo de Parecerista',
            'pitRit' => 'Tipo',
            'parecer' => 'Parecer',
            'data' => 'Data do parecer',
            'comentario' => 'Mensagem',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoRelatorio()
    {
        return $this->hasOne(SispitPlanoRelatorio::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
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
        if($this->comissao_pit_rit)
            return 'Comissão de Avaliação de PIT e RIT Docente';

        return $this->pessoa->nome;
    }

    public function getTipo(){
        switch($this->tipo_parecerista){
            case self::PARECERISTA_NUCLEO:
                return 'Núcleo';
            case self::PARECERISTA_CAC:
                return 'CAC';
        }
    }

    public function getPitRit(){
        return ($this->pit_rit == 0 ? 'PIT' : 'RIT');
    }

    public function isEditable(){
        if(!$this->atual || $this->id_pessoa != Yii::$app->session->get('siscat_pessoa')->id_pessoa)
            return false;
        switch($this->planoRelatorio->status){
            case 2:case 3:case 4:
                if($this->tipo_parecerista != self::PARECERISTA_NUCLEO || $this->pit_rit)
                    return false;
            break;
            case 6:case 7: case 8:
                if($this->tipo_parecerista != self::PARECERISTA_CAC || $this->pit_rit)
                    return false;
            break;
            case 12:case 13:case 14:
                if($this->tipo_parecerista != self::PARECERISTA_NUCLEO || !$this->pit_rit)
                    return false;
            break;
            case 16:case 17: case 18:
                if($this->tipo_parecerista != self::PARECERISTA_CAC || !$this->pit_rit)
                    return false;
            break;
            default:
                return false;
        }
        return true;
    }
}
