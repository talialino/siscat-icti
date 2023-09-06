<?php 

use yii\helpers\Html;
use app\assets\SisapeAsset;
use app\modules\sisape\models\SisapeProjeto;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
SisapeAsset::register($this);

$this->title = 'Resumo';
?>
<div class="sisape-default-index">
    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
        <div class="panel panel-primary">
            <div class="panel-heading">    
                <h3 class="panel-title">Informações</h3>
                <div class="clearfix"></div>
            </div>
            <div class="sisape-resumo table-responsive">
            <div class="sisape-projeto-search">
                <div class="row">
                <?php $form = ActiveForm::begin(['action' => 'resumo']); ?>
                    <div class="col-md-3">
                    <?= Html::dropDownList('tipo_projeto',$tipo_projeto,SisapeProjeto::TIPO_PROJETO,['prompt' => 'Todos os tipos', 'class'=>'form-control']) ?>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <?= Html::submitButton('Filtrar', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
                </div>
            </div>
                <table class="kv-grid-table table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Situação do Projeto</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php foreach($dados as $valor):?>
                    <tr>
                    <td><?=SisapeProjeto::SITUACAO[$valor['situacao']]?></td>
                    <td><?=$valor['total']?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
                </table>
            </div>
        </div>
</div>
