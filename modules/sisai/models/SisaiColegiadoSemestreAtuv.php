<?php

namespace app\modules\sisai\models;

use app\modules\siscc\models\SisccSemestre;
use app\modules\sisrh\models\SisrhSetor;
use Yii;

/**
 * This is the model class for table "sisai_colegiado_semestre_atuv".
 *
 * @property int $id_colegiado_semestre_atuv
 * @property string $colegiados_liberados
 * @property int $id_semestre
 *
 * @property SisccSemestre $semestre
 */
class SisaiColegiadoSemestreAtuv extends \yii\db\ActiveRecord
{
    public const DEFAULT_VALUE_COLEGIADOS = [11,12,13,14,15,16,17]; //Colegiados da graduação
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisai_colegiado_semestre_atuv';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_semestre'], 'required'],
            [['id_semestre'], 'integer'],
            [['colegiados_liberados'], 'string', 'max' => 255],
            [['id_semestre'], 'exist', 'skipOnError' => true, 'targetClass' => SisccSemestre::class, 'targetAttribute' => ['id_semestre' => 'id_semestre']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'colegiados_liberados' => Yii::t('app', 'Colegiados Liberados'),
            'id_semestre' => Yii::t('app', 'Semestre'),
        ];
    }

    public function getColegiadosLiberados()
    {
        $colegiados = SisrhSetor::find()->select('nome')->where(['id_setor' => $this->colegiados_liberados])->all();
        $saida = '';
        foreach($colegiados as $colegiado)
            $saida .= " - $colegiado->colegiado";
        
        return $saida;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemestre()
    {
        return $this->hasOne(SisccSemestre::class, ['id_semestre' => 'id_semestre']);
    }

    public function beforeValidate()
    {
        if(!is_array($this->colegiados_liberados))
            return false;
        $this->colegiados_liberados = serialize($this->colegiados_liberados);

        return parent::beforeValidate();
    }

    public function afterFind()
    {
        parent::afterFind();
        if($this->colegiados_liberados != null)
            $this->colegiados_liberados = unserialize($this->colegiados_liberados);
        
    }
}
