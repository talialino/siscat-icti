<?php

namespace app\modules\sisai\models;

use app\modules\siscc\models\SisccComponenteCurricular;
use app\modules\sisrh\models\SisrhSetor;
use Exception;
use Throwable;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "sisai_professor_componente_curricular".
 *
 * @property int $id_professor_componente_curricular
 * @property int $id_avaliacao
 * @property int $id_componente_curricular
 * @property int $id_setor
 * @property int $concluida
 *
 * @property SisaiAvaliacao $avaliacao
 * @property SisccComponenteCurricular $componenteCurricular
 * @property SisrhSetor $setor
 */
class SisaiProfessorComponenteCurricular extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisai_professor_componente_curricular';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_avaliacao', 'id_componente_curricular', 'id_setor'], 'required'],
            [['id_avaliacao', 'id_componente_curricular', 'id_setor', 'concluida'], 'integer'],
            [['id_avaliacao'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiAvaliacao::class, 'targetAttribute' => ['id_avaliacao' => 'id_avaliacao']],
            [['id_componente_curricular'], 'exist', 'skipOnError' => true, 'targetClass' => SisccComponenteCurricular::class, 'targetAttribute' => ['id_componente_curricular' => 'id_componente_curricular']],
            [['id_setor'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhSetor::class, 'targetAttribute' => ['id_setor' => 'id_setor']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_professor_componente_curricular' => Yii::t('app', 'Id Professor Componente Curricular'),
            'id_avaliacao' => Yii::t('app', 'Id Avaliacao'),
            'id_componente_curricular' => Yii::t('app', 'Id Componente Curricular'),
            'id_setor' => Yii::t('app', 'Id Setor'),
            'concluida' => Yii::t('app', 'Concluida'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAvaliacao()
    {
        return $this->hasOne(SisaiAvaliacao::class, ['id_avaliacao' => 'id_avaliacao']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComponenteCurricular()
    {
        return $this->hasOne(SisccComponenteCurricular::class, ['id_componente_curricular' => 'id_componente_curricular']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSetor()
    {
        return $this->hasOne(SisrhSetor::class, ['id_setor' => 'id_setor']);
    }

    /**
     * Salva os componentes curriculares no BD
     * @param SisaiAvaliacao $avaliacao
     * @param ActiveQuery $query
     * @return bool
     */
    public static function importarComponentes($avaliacao, $query)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $houvealteracao = false;
            foreach($query->all() as $componente){
                
                //Caso o aluno esteja adicionando um componente depois de já ter feito outras avaliações
                //o sistema verifica se o componente que ele está querendo adicionar não foi avaliado por ele.
                if($avaliacao->situacao > 1 && SisaiProfessorComponenteCurricular::find()->where([
                    'id_avaliacao' => $avaliacao->id_avaliacao, 'id_setor' => $componente->id_setor,
                    'id_componente_curricular' => $componente->id_componente_curricular,
                ])->exists())
                    continue;
                
                $professorcc = new SisaiProfessorComponenteCurricular();
                $professorcc->id_avaliacao = $avaliacao->id_avaliacao;
                $professorcc->id_setor = $componente->id_setor;
                $professorcc->id_componente_curricular = $componente->id_componente_curricular;
                
                if(!$professorcc->save())
                    throw new Exception('');
                $houvealteracao = true;
            }
            
            if($avaliacao->situacao == 0)
                $avaliacao->situacao = 3;

            else if($avaliacao->situacao == 99)
            {
                if(!$houvealteracao){
                    $transaction->rollBack();
                    return true;
                }
                else
                    $avaliacao->situacao = 97;
            }
            
            if(!$avaliacao->save())
                throw new Exception('');

            $transaction->commit();
            return true;
        }
        catch(Throwable $e)
        {
            $transaction->rollBack();
            return false;
        }
    }
}
