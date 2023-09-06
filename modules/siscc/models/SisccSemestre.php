<?php

namespace app\modules\siscc\models;

use app\modules\sisai\models\SisaiAvaliacao;
use Yii;

/**
 * This is the model class for table "siscc_semestre".
 *
 * @property int $id_semestre
 * @property int $ano
 * @property int $semestre
 * @property int $remoto
 *
 * @property SisaiAvaliacao[] $sisaiAvaliacaos
 * @property SisccProgramaComponenteCurricular[] $sisccProgramaComponenteCurriculars
 */
class SisccSemestre extends \yii\db\ActiveRecord
{
    public const OPCOESSEMESTRE = [
        1 => '1',
        2 => '2',
        3 => 'Suplementar',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siscc_semestre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ano', 'semestre', 'remoto'], 'required'],
            [['ano', 'semestre', 'remoto'], 'integer'],
            [['id_semestre'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ano' => 'Ano',
            'semestre' => 'Semestre',
            'remoto' => 'Remoto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiAvaliacaos()
    {
        return $this->hasMany(SisaiAvaliacao::class, ['id_semestre' => 'id_semestre']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisccProgramaComponenteCurriculars()
    {
        return $this->hasMany(SisccProgramaComponenteCurricular::class, ['id_semestre' => 'id_semestre']);
    }

    /**
     * A função a seguir informa se é possível habilitar a função de importar semestre
     * Esta função só está disponível caso seja um semestre regular (não pode ser suplementar)
     * e caso não tenha nenhum programa já previamente adicionado.
     * @return bool
     */
    public function podeImportar()
    {
        if($this->semestre > 2)
            return false;
        else
            return !$this->getSisccProgramaComponenteCurriculars()->exists();
    }

    public function getString()
    {
        if($this->semestre == 3)
            return "SLS $this->ano";
            //return "$this->ano {$this::OPCOESSEMESTRE[$this->semestre]}";
        return "$this->ano.$this->semestre";
    }
}
