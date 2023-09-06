<?php

namespace app\modules\siscoae\models;

use DirectoryIterator;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class FormularioSocioeconomicoForm extends Model
{
    public $arquivoCsv;
    public $nome;

    public function attributeLabels()
    {
        return [
            'arquivoCsv' => 'Arquivo com a base de dados',
            'nome' => 'Nome do arquivo (não usar acentos ou caracteres especiais)',
        ];
    }
    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['nome'], 'string', 'max' => 100],
            [['arquivoCsv'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv'],
        ];
    }

    public function listaArquivos()
    {
        $arquivos = [];
        $diretorio = new DirectoryIterator('uploads/coae/formularios');
        foreach($diretorio as $dadosArquivo)
            if($dadosArquivo->getExtension() == 'csv')
                $arquivos[$dadosArquivo->getFilename()] = $dadosArquivo->getFilename();
        return $arquivos;
    }

    public function upload()
    {
        $nome = str_replace(' ','_',$this->nome);
        if(!file_exists('uploads/coae'))
        {
            mkdir('uploads/coae');
            mkdir('uploads/coae/formularios');
        }
        return $this->arquivoCsv->saveAs("uploads/coae/formularios/$nome.csv");
    }

    public function lerRegistrosCsv()
    {
        $reader = new Csv();
        $reader->setInputEncoding('UTF-8');
        $reader->setDelimiter(',');
        $reader->setEnclosure('"');
        $reader->setSheetIndex(0);
        $worksheet = $reader->load("uploads/coae/formularios/$this->nome")->getActiveSheet();
        $saida = array();
        for($i = 2; $i <= $worksheet->getHighestRow(); $i++)
            $saida[$i - 1] = $worksheet->getRowIterator($i,$i)->current()->getCellIterator()->current();
        return $saida;
    }

    public function csvToArray($linha = 2)
    {
        $reader = new Csv();
        $reader->setInputEncoding('UTF-8');
        $reader->setDelimiter(',');
        $reader->setEnclosure('"');
        $reader->setSheetIndex(0);
        $worksheet = $reader->load("uploads/coae/formularios/$this->nome")->getActiveSheet();
        if($linha <= 1 || $linha > $worksheet->getHighestRow())
            throw new NotFoundHttpException('Página não encontrada');
        
        $row = $worksheet->getRowIterator($linha,$linha)->current();
        $saida = array();
        foreach($row->getCellIterator() as $cell)
        {
            $saida[] = $cell->getValue();
        }
        return $saida;
    }
}