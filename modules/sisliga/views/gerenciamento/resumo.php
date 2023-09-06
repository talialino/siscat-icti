<?php 

use yii\helpers\Html;
use app\assets\SisligaAsset;
use app\modules\sisliga\models\SisligaLigaAcademica;
SisligaAsset::register($this);

$this->title = 'Resumo';
?>
<div class="sisliga-default-index">
    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
        <div class="panel panel-primary">
            <div class="panel-heading">    
                <h3 class="panel-title">Informações</h3>
                <div class="clearfix"></div>
            </div>
            <div class="sisliga-resumo table-responsive">
                <table class="kv-grid-table table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Situação da Liga</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php foreach($dados as $valor):?>
                    <tr>
                    <td><?=SisligaLigaAcademica::SITUACAO[$valor['situacao']]?></td>
                    <td><?=$valor['total']?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
                </table>
            </div>
        </div>
</div>
