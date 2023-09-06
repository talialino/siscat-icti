<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use kartik\mpdf\Pdf;
use yii\helpers\Url;

class SiscatPdfWidget extends Widget
{
    public $mode;
    public $format;
    public $orientation;
    public $destination;
    public $nome;
    public $filename;
    public $modulo;
    public $cabecalho;
    public $rodape;
    public $cssInLine;
    public $cssFile;
    public $marginFooter;
    public $marginTop;
    public $title;

    public function init()
    {
        parent::init();
        if($this->mode === null)
            $this->mode = Pdf::MODE_CORE;
        if($this->format === null)
            $this->format = Pdf::FORMAT_A4;
        if($this->orientation === null)
            $this->orientation = Pdf::ORIENT_PORTRAIT;
        if($this->destination === null)
            $this->destination = Pdf::DEST_DOWNLOAD;
        if($this->nome === null)
            $this->nome = 'Siscat';
        if($this->filename === null)
            $this->filename = "$this->nome.pdf";
        //$brasaoUfba = Url::to('@web/images/brasao_ufba.jpg');
        $brasaoUfba = "https://siscat.ims.ufba.br/siscat/images/brasao_ufba.jpg";
        if($this->modulo === null)
            $this->modulo = "SISCAT - Sistema do IMS/CAT";
        if($this->cabecalho === null)
            $this->cabecalho = <<<EOD
            <table style="border:0; padding-bottom:5px">
                <tr style="border:0;">
                    <td style="border:0; width:15%"><img src="$brasaoUfba"></td>
                    <td style="border:0; width:85%; font-size:16px; font-weight:bold">
                    Universidade Federal da Bahia - Campus Anísio Teixeira<br>
                    Instituto Multidisciplinar em Saúde<br>
                    $this->modulo</td>
                </tr>
        </table>        	
EOD;
        if($this->rodape === null)
            $this->rodape = <<<EOD
            <span style="font-size:10px; text-align:center; color:grey">
            IMS/CAT-UFBA - Rua Rio de Contas, 58 - Quadra 17 - Lote 58 - Bairro Candeias <br>
            Vitória da Conquista - BA - CEP: 45.029-094/ Fone: (77)3429 2709. 
            E-mail:catims@ufba.br</span>      	
EOD;
        if($this->cssInLine === null)
            $this->cssInLine = '';
        if($this->cssFile === null)
            $this->cssFile = '';
        if($this->marginFooter === null)
            $this->marginFooter = 5;
        if($this->marginTop === null)
            $this->marginTop = 40;
        if($this->title === null)
            $this->title = "SISCAT";
        ob_start();
    }

    public function run()
    {
        $content = ob_get_clean();

        $pdf = new Pdf([          
            'mode' => $this->mode,
            'format' => $this->format,
            'orientation' => $this->orientation,
            'destination' => $this->destination,
            'filename' => $this->filename,
            'content' => $content,
            'cssInline' => $this->cssInLine,
            'marginFooter' => $this->marginFooter,
            'marginTop' => $this->marginTop,
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => [$this->title],
                'SetHeader' => [$this->cabecalho],
                'SetFooter' => [$this->rodape],
            ]
        ]);

        return $pdf->render();
    }
}