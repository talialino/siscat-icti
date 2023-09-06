<?php

namespace app\modules\sisai\models;

use Yii;
use yii\base\Model;

class RelatorioQuantitativo extends Model
{
    public $pergunta;
    public $plenamente_satisfatorio;
    public $satisfatorio;
    public $regular;
    public $pouco_satisfatorio;
    public $insatisfatorio;

    public function __construct($p,$pl,$s,$r,$po,$i)
    {
        $this->pergunta = $p;
        $this->plenamente_satisfatorio = $pl;
        $this->satisfatorio = $s;
        $this->regular = $r;
        $this->pouco_satisfatorio = $po;
        $this->insatisfatorio = $i;
    }

    public function attributeLabels()
    {
        return [
            'pergunta' => 'Pergunta',
            'plenamente_satisfatorio' => 'Plenamente Satisfatório',
            'satisfatorio' => 'Satisfatório',
            'regular' => 'Regular',
            'pouco_satisfatorio' => 'Pouco Satisfatório',
            'insatisfatorio' => 'Insatisfatório',
        ];
    }

    public function getTotal()
    {
        return $this->plenamente_satisfatorio + $this->satisfatorio + $this->regular + $this->pouco_satisfatorio + $this->insatisfatorio;
    }

    public function getMedia()
    {
        return 2 * ($this->plenamente_satisfatorio * 5 + $this->satisfatorio * 4 + $this->regular * 3 +
            $this->pouco_satisfatorio * 2 + $this->insatisfatorio) / $this->total; 
    }

    public function getPerguntaResumida() // usada para gráfico de componentes na modalidade online
    {
        $pergunta = $this->pergunta;
        // if(stripos($pergunta,'(Entenda'))
        //     $pergunta = substr($pergunta, 0, stripos($pergunta,'(Entenda'));
        while(stripos($pergunta,'('))
        {
            $inicio = stripos($pergunta,'(');
            $fim = stripos($pergunta,')') + 1;
            $pergunta = substr($pergunta, 0, $inicio).substr($pergunta,$fim);
        }
        if(strlen($pergunta) <= 100)
            return str_replace('  ', ' ', $pergunta);
        return str_replace(['componente curricular', 'os alunos a estabelecerem ', 'componentes curriculares', 'com as atividades profissionais', '  '],
            ['CC','', 'CCs', 'atividades profissionais', ' '],$pergunta);
    }

    public function getPerguntaFracionada($tamanho = 100)
    {
        return $this->dividirString($this->pergunta, $tamanho);
    }

    protected function dividirString(string $texto, $tamanho)
    {
        if(strlen($texto) <= $tamanho)
            return $texto;
        $indice = $tamanho;
        while(substr($texto,$indice,1) != ' ')
            $indice--;
        $resto = $this->dividirString(substr($texto,$indice + 1), $tamanho);
        return is_array($resto) ? array_merge([substr($texto,0,$indice)], $resto) :
            [substr($texto,0,$indice), $resto];
    }

    public static function removerExplicacao(string $pergunta)
    {
        if(stripos($pergunta,'(Entenda'))
            return substr($pergunta, 0, stripos($pergunta,'(Entenda'));
        return $pergunta;
    }
}