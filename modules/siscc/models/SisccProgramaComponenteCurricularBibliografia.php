<?php

namespace app\modules\siscc\models;

use Yii;

/**
 * This is the model class for table "siscc_programa_componente_curricular_bibliografia".
 *
 * @property int $id_programa_componente_curricular
 * @property int $id_bibliografia
 * @property int $tipo_referencia
 *
 * @property SisccProgramaComponenteCurricular $programaComponenteCurricular
 * @property SisccBibliografia $bibliografia
 */
class SisccProgramaComponenteCurricularBibliografia extends \yii\db\ActiveRecord
{
    public const TIPO_REFERENCIA = [
        1 => 'Bibliografia Básica',
        2 => 'Bibliografia Complementar',
        3 => 'Sugestão de Bibliografia Básica',
        4 => 'Sugestão de Bibliografia Complementar',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siscc_programa_componente_curricular_bibliografia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_programa_componente_curricular', 'id_bibliografia', 'tipo_referencia'], 'required'],
            [['id_programa_componente_curricular', 'id_bibliografia', 'tipo_referencia'], 'integer'],
            [['id_programa_componente_curricular', 'id_bibliografia'], 'unique', 'targetAttribute' => ['id_programa_componente_curricular', 'id_bibliografia']],
            [['id_programa_componente_curricular'], 'exist', 'skipOnError' => true, 'targetClass' => SisccProgramaComponenteCurricular::className(), 'targetAttribute' => ['id_programa_componente_curricular' => 'id_programa_componente_curricular']],
            [['id_bibliografia'], 'exist', 'skipOnError' => true, 'targetClass' => SisccBibliografia::className(), 'targetAttribute' => ['id_bibliografia' => 'id_bibliografia']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_bibliografia' => 'Referência Bibliográfica',
            'tipo_referencia' => 'Tipo de Referência',

            'referencia' => 'Referência Bibliográfica',
            'tipo' => 'Tipo de Referência',
            'programaComponenteCurricular.componenteCurricular.codigo_componente' => 'Código do Componente',
            'programaComponenteCurricular.colegiado' => 'Colegiado',
            'programaComponenteCurricular.semestreString' => 'Semestre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramaComponenteCurricular()
    {
        return $this->hasOne(SisccProgramaComponenteCurricular::className(), ['id_programa_componente_curricular' => 'id_programa_componente_curricular']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBibliografia()
    {
        return $this->hasOne(SisccBibliografia::className(), ['id_bibliografia' => 'id_bibliografia']);
    }

    public function getReferencia()
    {
        return $this->bibliografia->nome;
    }

    public function getTipo()
    {
        return self::TIPO_REFERENCIA[$this->tipo_referencia];
    }
}
