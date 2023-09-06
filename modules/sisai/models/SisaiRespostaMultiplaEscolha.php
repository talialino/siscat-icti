<?php

namespace app\modules\sisai\models;

use Exception;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "sisai_resposta_multipla_escolha".
 *
 * @property int $id_sisai_resposta_multipla_escolha
 * @property string $descricao
 * @property int $id_pergunta
 *
 * @property SisaiPergunta $pergunta
 */
class SisaiRespostaMultiplaEscolha extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisai_resposta_multipla_escolha';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_sisai_resposta_multipla_escolha', 'descricao', 'id_pergunta'], 'required'],
            [['id_sisai_resposta_multipla_escolha', 'id_pergunta'], 'integer'],
            [['descricao'], 'string', 'max' => 255],
            [['id_sisai_resposta_multipla_escolha', 'id_pergunta'], 'unique', 'targetAttribute' => ['id_sisai_resposta_multipla_escolha', 'id_pergunta']],
            [['id_pergunta'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiPergunta::class, 'targetAttribute' => ['id_pergunta' => 'id_pergunta']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_sisai_resposta_multipla_escolha' => 'Id',
            'descricao' => 'Descrição',
            'id_pergunta' => 'Pergunta',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPergunta()
    {
        return $this->hasOne(SisaiPergunta::class, ['id_pergunta' => 'id_pergunta']);
    }

    /**
     * Salva as respostas armazenadas no atributo descricao, fazendo a separação das linhas e adicionando cada linha como uma resposta
     * @return bool
     */
    public function salvarMultiplasRespostas()
    {
        if($this->descricao == null || strlen($this->descricao) <= 0)
            return false;

        $respostas = explode("\n",$this->descricao);
        $query = new Query();
        $indice = $query->from('sisai_resposta_multipla_escolha')->
            where(['id_pergunta' => $this->id_pergunta])->max('id_sisai_resposta_multipla_escolha');
        if(!$indice)
            $indice = 0;
        $transaction = $this->getDb()->beginTransaction();
        try{
            foreach($respostas as $resposta){
                if(strlen($resposta) > 0){
                    $indice++;
                    $model = new SisaiRespostaMultiplaEscolha();
                    $model->id_pergunta = $this->id_pergunta;
                    $model->id_sisai_resposta_multipla_escolha = $indice;
                    $model->descricao = $resposta;
                    if(!$model->save()){
                        Yii::debug($model->getErrors());
                        throw new Exception('Deu ruim');
                    }
                }
            }
            $transaction->commit();
        }
        catch(Exception $e){
            $transaction->rollBack();
            return false;
        }

        return $indice > 0;
    }
}
