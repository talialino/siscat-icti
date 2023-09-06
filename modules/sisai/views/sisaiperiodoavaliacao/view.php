<?php

use app\modules\sisai\models\SisaiQuestionario;
use app\modules\siscc\models\SisccComponenteCurricular;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisrh\models\SisrhSetor;
use yii\helpers\Html;
use kartik\detail\DetailView;
use app\assets\SisaiAsset;

SisaiAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiPeriodoAvaliacao */

$this->title = 'Visualizar Período de Avaliação';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisai'), 'url' => ['/'.strtolower('sisai')]];
$this->params['breadcrumbs'][] = ['label' => 'Períodos de Avaliação', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//Esse comando serve para corrigir a data exibida, já que o sistema entende que a data armazenada no banco está em UTC.
//Quando exibia, ele fazia automaticamente a conversão para America/Bahia, o que reduzia em 3h o horário.
Yii::$app->setTimeZone('UTC');
$periodoAvaliacao = Yii::$app->session->get('siscat_periodo_avaliacao',false);
?>
<div class="sisai-periodo-avaliacao-view">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <?php if(!$periodoAvaliacao || $periodoAvaliacao->id_semestre == $model->id_semestre):?>
    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id_semestre], ['class' => 'btn btn-warning btn-lg pull-right']) ?>
        <?= Html::a('Excluir', ['delete', 'id' => $model->id_semestre], [
            'class' => 'btn btn-danger btn-lg pull-right',
            'data' => [
                'confirm' => 'Tem certeza que deseja remover este item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif;?>
    <div class="clearfix"></div>

    <?= DetailView::widget([
        'model' => $model,
        'vAlign' => 'top',
        'panel' => ['type' => 'primary', 'heading' => $this->title],
        'enableEditMode' => false,
        'attributes' => [
            
            ['attribute' => 'semestre', 'value' => $model->semestre->string],
            'data_inicio:datetime',
            'data_fim:datetime',
            [
                'attribute' => 'questionarios',
                'visible' => Yii::$app->user->can('siscatAdministrar'),
                'format' => 'html',
                'value' => function($form,$widget){
                    $saida = '<table class="periodo-avaliacao-questionarios table table-striped table-bordered">';
                    $saida .= '<thead> <tr> <th>Tipo de Questionário</th> <th>ID</th> <th>Título</th> </tr> </thead> <tbody>';
                    foreach((array)$widget->model->questionarios as $key => $value){
                        $tipoQuestionario = SisaiQuestionario::TIPO_QUESTIONARIO[$key];
                        $questionario = SisaiQuestionario::findOne($value);
                        $saida .= "<tr> <td>$tipoQuestionario</td> <td> $questionario->id_questionario</td> <td> $questionario->titulo</td></tr>";
                    }
                    $saida .= '</tbody></table>';
                    return $saida;
                }
            ],
            [
                'attribute' => 'componentes_estagio',
                'format' => 'html',
                'value' => function($form,$widget){
                    $saida = '<table class="periodo-avaliacao-componente-estagio table table-striped table-bordered">';
                    foreach((array)$widget->model->componentes_estagio as $id_componente_curricular)
                        $saida .= '<tr><td>'.SisccComponenteCurricular::findOne($id_componente_curricular)->codigoNome.'</td></tr>';
                    $saida .= '</table';
                    return $saida;
                }
            ],
            [
                'attribute' => 'componentes_online',
                'format' => 'html',
                'value' => function($form,$widget){
                    $saida = '<table class="periodo-avaliacao-componente-online table table-striped table-bordered">';
                    $saida .= '<thead> <tr> <th>Colegiado</th> <th>Componente</th> <th>Docente</th> </tr> </thead> <tbody>';
                    foreach((array)$widget->model->componentes_online as $array)
                    {
                        $colegiado = SisrhSetor::findOne($array['colegiado'])->colegiado;
                        $componente = SisccComponenteCurricular::findOne($array['componente'])->codigoNome;
                        $docente = SisrhPessoa::findOne($array['docente'])->nome;

                        
                        $saida .= "<tr> <td>$colegiado</td> <td>$componente</td> <td>$docente</td> </tr>";
                    }
                    $saida .= '</tbody></table>';
                    return $saida;
                }
            ],
        ],
    ]) ?>

</div>
