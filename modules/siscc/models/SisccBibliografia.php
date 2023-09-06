<?php

namespace app\modules\siscc\models;

use Yii;

/**
 * This is the model class for table "siscc_bibliografia".
 *
 * @property int $id_bibliografia
 * @property string $nome
 *
 * @property SisccProgramaComponenteCurricularBibliografia[] $sisccProgramaComponenteCurricularBibliografias
 * @property SisccProgramaComponenteCurricular[] $programaComponenteCurriculars
 */
class SisccBibliografia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siscc_bibliografia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['nome'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nome' => 'Referência bibliográfica',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisccProgramaComponenteCurricularBibliografias()
    {
        return $this->hasMany(SisccProgramaComponenteCurricularBibliografia::className(), ['id_bibliografia' => 'id_bibliografia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramaComponenteCurriculars()
    {
        return $this->hasMany(SisccProgramaComponenteCurricular::className(), ['id_programa_componente_curricular' => 'id_programa_componente_curricular'])->viaTable('siscc_programa_componente_curricular_bibliografia', ['id_bibliografia' => 'id_bibliografia']);
    }
}
