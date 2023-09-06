<?php

use yii\helpers\Html;

use app\modules\siscc\models\SisccSemestre;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$periodoAvaliacao = Yii::$app->session->get('siscat_periodo_avaliacao', false);
$semestreWhere = 'id_semestre > 22'.($periodoAvaliacao ? " AND id_semestre <> $periodoAvaliacao->id_semestre" : '');
?>
<div class="sisai-aluno-search">

<?php $form = ActiveForm::begin([]); ?>

    <div class="col-sm-6">
        <?= $form->field($model, 'id_semestre')->dropDownList(
            ArrayHelper::map(SisccSemestre::find()->where($semestreWhere)->orderby('ano DESC, semestre DESC')->all(),'id_semestre','string'),
            ['prompt' => 'Selecione o semestre']
        ) ?>
    </div>
    <div class="form-group col-sm-12">
        <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
<div class="clearfix"></div>
</div>