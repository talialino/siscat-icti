<?php

namespace app\modules\sisai\models;

use yii\base\Model;

class AtualizarTabelaAlunoForm extends Model
{
    public $arquivo;

    public function attributeLabels()
    {
        return [
            'arquivo' => 'Arquivo',
        ];
    }

    public function rules()
    {
        return [
            [['arquivo'], 'file', 'skipOnEmpty' => false, 'extensions' => 'txt'],
        ];
    }
}