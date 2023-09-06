<?php

use app\modules\sisai\models\ListaAtuvForm;
use app\modules\sisai\models\SisaiAluno;
use app\modules\sisrh\models\SisrhSetor;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

$spreadSheet = new Spreadsheet();

$spreadSheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

$sheet = $spreadSheet->getActiveSheet();

$sheet->getPageSetup()
    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()
    ->setPaperSize(PageSetup::PAPERSIZE_A4);

$yellow = new Color(Color::COLOR_YELLOW);
$lightGray = new Color('B2B2B2');

$bordas = [ 'allBorders' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => [ 'rgb' => '000000' ] ] ];

$linha = 1;
$colegiado = $nivel = false;
$sheet->setTitle('Alunos UFBA');
$sheet->mergeCells("A$linha:G$linha");
$sheet->setCellValue("A$linha",'Alunos dos cursos de Graduação – IMS/CAT – UFBA');
$estituloTitulo = $sheet->getStyle("A$linha");
$estituloTitulo->getFont()->setSize(14)->setBold(true);
$estituloTitulo->getFill()->setFillType('solid')->setStartColor($yellow);
$estituloTitulo->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$estituloTitulo->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$estituloTitulo->getBorders()->applyFromArray($bordas);
$sheet->getStyle("G$linha")->getBorders()->applyFromArray($bordas);
$sheet->getRowDimension($linha)->setRowHeight(38);
$linha++;

$tituloPos = false;

$sheet->getColumnDimension('A')->setWidth(60);
$sheet->getColumnDimension('B')->setWidth(11);
$sheet->getColumnDimension('C')->setWidth(13);
$sheet->getColumnDimension('D')->setWidth(11);
$sheet->getColumnDimension('E')->setWidth(11);
$sheet->getColumnDimension('F')->setWidth(14);
$sheet->getColumnDimension('G')->setWidth(10);

foreach($dataProvider as $data){
    if(!$colegiado || ($colegiado != SisrhSetor::ajustarNomeColegiado($data['colegiado'])) || $nivel != $data['nivel_escolaridade'])
    {
        $colegiado = SisrhSetor::ajustarNomeColegiado($data['colegiado']);
        $nivel = $data['nivel_escolaridade'];
        if($nivel > 1 && !$tituloPos){
            $linha += 2;
            $sheet->mergeCells("A$linha:G$linha");
            $sheet->setCellValue("A$linha",'Alunos dos cursos de Pós-Graduação – não semestralizados – IMS/CAT – UFBA');
            $estituloTitulo = $sheet->getStyle("A$linha");
            $estituloTitulo->getFont()->setSize(14)->setBold(true);
            $estituloTitulo->getFill()->setFillType('solid')->setStartColor($yellow);
            $estituloTitulo->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $estituloTitulo->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $estituloTitulo->getBorders()->applyFromArray($bordas);
            $sheet->getStyle("G$linha")->getBorders()->applyFromArray($bordas);
            $sheet->getRowDimension($linha)->setRowHeight(38);
            $linha++;

            $tituloPos = true;
        }
        $linha++;
        $sheet->setCellValue("A$linha",'Alunos do Curso'.($nivel != 5 ? ' de '.SisaiAluno::ESCOLARIDADE[$nivel] : ''). " de $colegiado");
        $sheet->setCellValue("B$linha",'Matrícula');
        $sheet->setCellValue("C$linha",'Ano de entrada');
        $sheet->setCellValue("D$linha",'Semestre matriculado');
        if($nivel == 1){
            $sheet->setCellValue("E$linha",'N de semestres cursados');
            $sheet->setCellValue("F$linha",'Semestre provável/regular do aluno');
            $sheet->setCellValue("G$linha",'Turno');
        }
        else
            $sheet->setCellValue("E$linha",'Turno');

        $estiloCelula = $sheet->getStyle($nivel == 1 ? "A$linha:G$linha" : "A$linha:E$linha");
        $estiloCelula->getAlignment()->setWrapText(true);
        $estiloCelula->getFont()->setSize(9)->setBold(true);
        $estiloCelula->getFill()->setFillType('solid')->setStartColor($lightGray);
        $estiloCelula->getBorders()->applyFromArray($bordas);
        $sheet->getRowDimension($linha)->setRowHeight(38);

        $linha++;
    }
    
    $sheet->setCellValue("A$linha",$data['nome']);
    $sheet->setCellValue("B$linha",$data['matricula']);
    $sheet->setCellValue("C$linha","{$data['anoEntrada']}-{$data['semestreEntrada']}");
    $sheet->setCellValue("D$linha","{$data['anoAtual']}-{$data['semestreAtual']}");
    if($nivel == 1){
        $sheet->setCellValue("E$linha",ListaAtuvForm::semestresCursados($data['anoEntrada'], $data['semestreEntrada'], $data['anoAtual'],
            $data['semestreAtual']));
        $sheet->setCellValue("F$linha",ListaAtuvForm::semestresCursados($data['anoEntrada'], $data['semestreEntrada'], $data['anoAtual'],
            $data['semestreAtual']) + 1);
        $sheet->setCellValue("G$linha",'Diurno');
    }
    else
        $sheet->setCellValue("E$linha",'Diurno');

    $sheet->getStyle($nivel == 1 ? "A$linha:G$linha" : "A$linha:E$linha")->getBorders()->applyFromArray($bordas);
    $sheet->getStyle($nivel == 1 ? "B$linha:G$linha" : "B$linha:E$linha")->getAlignment()->setHorizontal('right');
    $linha++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="alunos.xlsx"');
header('Cache-Control: max-age=0');


$writer = new Xlsx($spreadSheet);
$writer->save('php://output');
exit();