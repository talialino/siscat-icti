<?php 

use yii\helpers\Html;
use app\assets\SispitAsset;
use app\modules\sispit\models\SispitPlanoRelatorio;
SispitAsset::register($this);

$this->title = 'Resumo';
?>
<div class="sispit-default-index">
    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
        <div class="panel panel-primary">
            <div class="panel-heading">    
                <h3 class="panel-title">Informações</h3>
                <div class="clearfix"></div>
            </div>
            <div class="sispit-resumo table-responsive">
                <table class="kv-grid-table table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Situação do PIT/RIT</th>
                        <th>Quantidade de docentes</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php foreach(SispitPlanoRelatorio::SITUACAO as $k => $v):?>
                    <tr>
                    <td><?=$v?></td>
                    <td><?=isset($dados[$k]) ? $dados[$k] : 0?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
                </table>
            </div>
        </div>
</div>
