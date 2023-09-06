<?php

namespace app\modules\sispit\models;

use Yii;
use yii\data\ArrayDataProvider;

class ResumoCargaHoraria extends  \yii\base\Model
{
    public $descricao;
    public $semestre1;
    public $semestre2;

    public function attributeLabels()
    {
        return [
            'descricao' => 'Atividades',
            'semestre1' => '1º Semestre',
            'semestre2' => '2º Semestre'
        ];
    }

    public static function fromArray($array)
    {
        $saida = array();
        
        foreach($array as $key => $value)
        {
            
            $resumo = new ResumoCargaHoraria;
            $resumo->descricao = $key;
            $resumo->semestre1 = $value[0]['total'];
            $resumo->semestre2 = (count($value) > 1) ? $value[1]['total'] : 0;
            $saida[] = $resumo;
        }
        return new ArrayDataProvider(['allModels' => $saida, 'sort' => false, 'pagination' => false]);
    }
}