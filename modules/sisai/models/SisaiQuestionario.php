<?php

namespace app\modules\sisai\models;

use Exception;
use Yii;

/**
 * This is the model class for table "sisai_questionario".
 *
 * @property int $id_questionario
 * @property string $titulo
 * @property int $tipo_questionario
 *
 * @property SisaiGrupoPerguntas[] $sisaiGrupoPerguntas
 */
class SisaiQuestionario extends \yii\db\ActiveRecord
{
    public const TIPO_QUESTIONARIO = [
         1 => 'Autoavaliação discente',
         2 => 'Avaliação docente por discente',
         3 => 'Autoavaliação docente',
         4 => 'Avaliação discente por docente',
         5 => 'Avaliação docente por discente (estágio)',
         6 => 'Autoavaliação docente (estágio)',
         7 => 'Avaliação do AVA por discentes',
         8 => 'Avaliação do AVA por docentes',
         9 => 'Avaliação de infraestrutura por docentes',
        10 => 'Avaliação da acessibilidade',
        11 => 'Avaliação da gestão acadêmica por discentes',
        12 => 'Avaliação da gestão acadêmica por docentes',
        13 => 'Avaliação da qualidade de vida',
        14 => 'Avaliação das condições de trabalho',
        15 => 'Avaliação dos técnicos',
        16 => 'Avaliação docente por discente (online)',
        17 => 'Avaliação de infraestrutura por discentes',
        18 => 'Avaliação de colegiado',
        19 => 'Avaliação de infraestrutura por técnicos',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisai_questionario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['titulo'], 'required'],
            [['tipo_questionario'], 'integer'],
            [['titulo'], 'string', 'max' => 75],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_questionario' => 'ID do Questionário',
            'titulo' => 'Título',
            'tipo_questionario' => 'Tipo de Questionário',
            'tipoQuestionario' => 'Tipo de Questionário',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiGrupoPerguntas()
    {
        return $this->hasMany(SisaiGrupoPerguntas::class, ['id_questionario' => 'id_questionario'])->orderBy('id_grupo_perguntas');
    }

    /**
     * Função que retorna as perguntas vinculadas ao primeiro grupo de perguntas do questionário. Ideal para os questionários de grupo único
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiPerguntas()
    {
        return $this->getSisaiGrupoPerguntas()->one()->getSisaiPerguntas();
    }
    /**
     * @return string
     */
    public function getTipoQuestionario()
    {
        if($this->tipo_questionario == null)
            return null;
        return self::TIPO_QUESTIONARIO[$this->tipo_questionario];
    }

    /**
     * Modifica o método de inserir para criar automaticamente um grupo de perguntas referente ao questionário
     */
    public function insert($runValidation = true, $attributes = null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(parent::insert($runValidation,$attributes))
            {
                $grupo = new SisaiGrupoPerguntas();
                $grupo->id_questionario = $this->id_questionario;
                if($grupo->save())
                {
                    $transaction->commit();
                    return true;
                }

            }
        }
        catch(Exception $e)
        {
            Yii::debug($e->getMessage());
        }

        $transaction->rollBack();
        return false;
    }
}
