<?php

use yii\helpers\Html;

use app\modules\siscc\models\SisccSemestre;
use app\modules\sisrh\models\SisrhPessoa;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->registerJs('
function carregarComponentes()
{
    const pessoa = document.getElementById("relatorioform-id_pessoa");
    const semestre = document.getElementById("relatorioform-id_semestre");
    const componente = document.getElementById("relatorioform-componente_colegiado")
    componente.innerHTML = "<option value></option>";
    componente.value = "";
    if(pessoa.value =="" || semestre.value == "")
        return;
    else
    {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            componente.innerHTML = this.responseText;
        }
        
        xhttp.open("GET", "ajaxcomponentescurriculares?id_pessoa=" + pessoa.value +
            "&id_semestre=" + semestre.value);
        xhttp.send();
    }
}

',View::POS_HEAD,'selectAction');
$periodoAvaliacao = Yii::$app->session->get('siscat_periodo_avaliacao', false);
$semestreWhere = 'id_semestre <> 21'.($periodoAvaliacao ? " AND id_semestre <> $periodoAvaliacao->id_semestre" : '');
?>
<div class="sisai-aluno-search">

<?php $form = ActiveForm::begin([]); ?>


    <?php if(Yii::$app->user->can('sisaiAdministrar')):?>
        <div class="col-sm-12">
    <?= $form->field($model, 'id_pessoa')->widget(Select2::class,[
        'data' => ArrayHelper::map(SisrhPessoa::find()->where(['situacao' => 1,'id_cargo' => 1])->orderby('nome')->all(),'id_pessoa','nome'),
        'options' => ['placeholder' => 'Selecione o docente', 'onchange' => 'carregarComponentes()'],
        'pluginOptions' => ['allowClear' =>true]
    ]) ?>
    </div>
    <?php else: $pessoa = Yii::$app->session->get('siscat_pessoa');?>
        <div class="col-sm-12">
        <?= $form->field($model, 'id_pessoa')->dropDownList([$pessoa->id_pessoa => $pessoa->nome,]) ?>
    </div>
    <?php endif;?>

    <div class="col-sm-6">
        <?= $form->field($model, 'id_semestre')->dropDownList(
            ArrayHelper::map(SisccSemestre::find()->where($semestreWhere)->orderby('ano DESC, semestre DESC')->all(),'id_semestre','string'),
            ['prompt' => 'Selecione o semestre', 'onchange' => 'carregarComponentes()']
        ) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'componente_colegiado')->dropDownList($model->listaComponentesColegiados(), ['prompt' => '']) ?>
    </div>
    <div class="form-group col-sm-12">
        <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
<div class="clearfix"></div>
</div>