<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\siscc\models\SisccParecerSearch;

$model = SisccParecerSearch::ultimoParecer($programa->id_programa_componente_curricular);

if($model != null):
?>
    <div class="siscc-ultimo-parecer"> 
        <h3 class="ultimo-parecer">Parecer mais recente</h3>
        <hr>
        <div class="">
            <?=$model->parecer?>
        </div>
        <hr>
        <p style="text-align: right;font-weight:bold;">Caso não concorde com o parecer <button type="button" onclick="$('.siscc-parecer-comentario').show();" class="btn btn-warning">Clique aqui</button></p>
    </div>
    
    <div class="siscc-parecer-comentario" style='display:none;'>
        <div class="mensagem-alerta">
            <p>Ao clicar em enviar, o programa será submetido novamente a(o) parecerista e não estará disponível para edição.</p>
            <p>Se precisar alterar alguma informação, faça isso <strong>ANTES</strong> de enviar sua mensagem.</p>
        </div>
        <?php $form = ActiveForm::begin([
            'options' => ['autocomplete' => 'off'],
            'action' => Url::to(['submeter', 'id' => $programa->id_programa_componente_curricular,'id_parecer' => $model->id_parecer]),
        ]); ?>

        <?= $form->field($model, 'comentario')->textarea(['rows' => 6]) ?>

        <div class="form-group">
            <?= Html::submitButton('Enviar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
<?php endif;?>