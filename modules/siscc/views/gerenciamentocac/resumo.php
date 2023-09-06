<?php 

use yii\helpers\Html;
use app\assets\SisccAsset;
use app\modules\siscc\models\SisccProgramaComponenteCurricular;
use yii\widgets\ActiveForm;
use app\modules\siscc\models\SisccSemestre;
use yii\helpers\ArrayHelper;
SisccAsset::register($this);

$this->title = 'Resumo';
?>
<div class="siscc-default-index">
    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
        <div class="panel panel-primary">
            <div class="panel-heading">    
                <h3 class="panel-title">Informações</h3>
                <div class="clearfix"></div>
            </div>
            <div class="siscc-resumo table-responsive">
            <div class="siscc-programa-componente-curricular-search">
                <div class="row">
                <?php $form = ActiveForm::begin(['action' => 'resumo']); ?>
                    <div class="col-md-3">
                    <?= Html::dropDownList('semestre',$semestre,ArrayHelper::map(SisccSemestre::find()->orderby('ano DESC, semestre DESC')->all(),'id_semestre','string'), ['prompt' => 'Todos os semestres', 'class'=>'form-control']) ?>
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
                        <th>Situação do Programa de Componente Curricular</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php foreach($dados as $valor):?>
                    <tr>
                    <td><?=SisccProgramaComponenteCurricular::SITUACAO[$valor['situacao']]?></td>
                    <td><?=$valor['total']?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
                </table>
            </div>
        </div>
</div>
