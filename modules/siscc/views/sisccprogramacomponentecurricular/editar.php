<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\modules\siscc\models\SisccComponenteCurricular;
use app\modules\siscc\models\SisccSemestre;
use app\modules\sisrh\models\SisrhSetor;
use kartik\detail\DetailView;
use dosamigos\tinymce\TinyMce;
use yii\web\JsExpression;
use yii\bootstrap\Modal;
use app\assets\SisccAsset;
use yii\bootstrap\Tabs;
SisccAsset::register($this);

$this->registerJsFile(
    '@web/js/siscctextoavancado.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricular */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Editar Programa de Componente Curricular';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = ['label' => 'Meus Programas', 'url' => ['/siscc/sisccprogramacomponentecurricularpessoa']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'header' => "<h3>$this->title</h3>",
    'id' => 'modal',
    'size' =>'modal-md',
    'options' => ['tabindex' => false,],
]);
echo "<div id='modalContent'></div>";
Modal::end();
?>
<h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
<?= DetailView::widget([
        'model' => $model->componenteCurricular,
        'panel' => ['type' => 'primary'],
        'mode' => 'view',
        'enableEditMode' => false,
        'attributes' => [
            ['label' => 'Semestre', 'value' => $model->semestreString],
            ['label' => 'Colegiado', 'value' => $model->colegiado],
            ['label' => 'Componente Curricular', 'value' => $model->componenteCurricular->codigoNome],
            ['label' => 'Modalidade/Submodalidade', 'value' => $model->componenteCurricular->modalidadeSubmodalidade],
            ['label' => 'Ementa', 'value' => $model->componenteCurricular->ementa],
        ],
    ]) ?>
<div class="siscc-programa-componente-curricular-form">
    <?= Tabs::widget([
            'options' => ['class' => 'responsive'],
            'itemOptions' => ['class' => 'responsive'],
            'items' => [
                [
                    'label' => 'Informações',
                    'active' => !$showAbaBibliografia,
                    'content' => $this->render('_editarinformacoes', ['model' => $model]),
                ],
                [
                    'label' => 'Bibliografia',
                    'active' => $showAbaBibliografia,
                    'content' => $this->render('_editarbibliografia', ['model' => $model, 'dataProvider' => $dataProvider]),
                ],
                [
                    'label' => 'Parecer',
                    'active' => false,
                    'content' => $this->render('_pareceres', ['programa' => $model]),
                    'visible' => $model->situacao == 4 || $model->situacao == 9,
                ]
            ],
    ]);
    ?>
</div>
