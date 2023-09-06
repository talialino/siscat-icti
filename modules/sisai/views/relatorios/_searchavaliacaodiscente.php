<?php

use yii\helpers\Html;

use app\modules\siscc\models\SisccSemestre;
use app\modules\sisrh\models\SisrhSetor;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;

$periodoAvaliacao = Yii::$app->session->get('siscat_periodo_avaliacao', false);
$semestreWhere = 'id_semestre <> 21'.($periodoAvaliacao ? " AND id_semestre <> $periodoAvaliacao->id_semestre" : '');
?>
<div class="sisai-aluno-search">

<?php $form = ActiveForm::begin([]); ?>

    <div class="col-sm-6">
        <?= $form->field($model, 'id_semestre')->dropDownList(
            ArrayHelper::map(SisccSemestre::find()->where($semestreWhere)->orderby('ano DESC, semestre DESC')->all(),'id_semestre','string'),
            ['prompt' => 'Selecione o semestre']
        ) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'id_setor')->label('Colegiado')->dropDownList(ArrayHelper::map(SisrhSetor::find()->where(['eh_colegiado' => 1])->all(), 'id_setor','colegiado'), ['prompt' => '']) ?>
    </div>
    <div class="form-group col-sm-12">
        <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
<div class="clearfix"></div>
</div>