<?php

namespace app\modules\siscc\models;

use Yii;

class SisccImportarProgramaForm extends yii\base\Model
{
    public $id_programa_componente_curricular;
    public $modificacoes;

    public function rules()
    {
        return [
            [['id_programa_componente_curricular','modificacoes'], 'required'],
            [['id_programa_componente_curricular','modificacoes'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_programa_componente_curricular' => 'Programa a ser importado',
            'modificacoes' => 'Deseja realizar modificações?',
        ];
    }
}