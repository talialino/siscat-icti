<?php

namespace app\modules\sispit\models;

use Yii;

/**
 * This is the model class for table "sispit_publicacao".
 *
 * @property int $id_publicacao
 * @property int $id_plano_relatorio
 * @property string $titulo
 * @property int $semestre
 * @property string $editora
 * @property string $local
 * @property string $fonte_financiadora
 * @property int $pit_rit
 *
 * @property SispitPlanoRelatorio $planoRelatorio
 */
class SispitPublicacao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sispit_publicacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plano_relatorio','semestre'], 'required'],
            [['id_plano_relatorio', 'semestre', 'pit_rit'], 'integer'],
            [['titulo'], 'string', 'max' => 200],
            [['editora', 'local'], 'string', 'max' => 100],
            [['fonte_financiadora'], 'string', 'max' => 20],
            [['id_plano_relatorio'], 'exist', 'skipOnError' => true, 'targetClass' => SispitPlanoRelatorio::className(), 'targetAttribute' => ['id_plano_relatorio' => 'id_plano_relatorio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'titulo' => 'Título',
            'semestre' => 'Semestre',
            'editora' => 'Editora',
            'local' => 'Local',
            'fonte_financiadora' => 'Fonte Financiadora',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoRelatorio()
    {
        return $this->hasOne(SispitPlanoRelatorio::className(), ['id_plano_relatorio' => 'id_plano_relatorio']);
    }
}
