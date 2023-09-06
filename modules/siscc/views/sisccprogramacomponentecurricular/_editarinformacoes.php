<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use yii\web\JsExpression;

?>

<?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>
        
    <?= $form->field($model, 'objetivo_geral')->textarea(['rows' => 6]) ?>
    
    <?= $form->field($model, 'objetivos_especificos')->widget(TinyMce::class, [
        'options' => ['rows' => 6],
        'language' => 'pt_BR',
        'clientOptions' => [
            'plugins' => [
                "lists paste",
            ],
            'toolbar' => false,
            'menubar' => false,
            'paste_as_text' => true,
            'forced_root_block' => "li",
            'setup' => new JsExpression('onkeyevent'),
        ]
    ]) ?>

    <?= $form->field($model, 'conteudo_programatico')->widget(TinyMce::class, [
        'options' => ['rows' => 6],
        'language' => 'pt_BR',
        'clientOptions' => [
            'plugins' => [
                "lists paste",
            ],
            'toolbar' => false,
            'menubar' => false,
            'paste_as_text' => true,
            'forced_root_block' => "li",
            'setup' => new JsExpression('onkeyevent'),
        ]
    ]) ?>
    
    <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>
