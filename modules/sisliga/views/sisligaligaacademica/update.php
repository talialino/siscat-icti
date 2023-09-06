<?php

use yii\helpers\Html;
use app\assets\SisligaAsset;
SisligaAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisliga\models\SisligaLigaAcademica */

$this->title = $this->title = Yii::t('app', 'Atualizar Liga');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisliga')), 'url' => ['/'.strtolower(Yii::t('app', 'sisliga'))]];
$this->params['breadcrumbs'][] = ['label' => 'Minhas Ligas Acadêmicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Visualizar Liga', 'url' => ['view', 'id' => $model->id_liga_academica]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->registerJs(
    "function escolherTipoIntegrante(campo){
        switch($(campo).val()){
            case '1':
                $('#id_pessoa').hide();
                $('#id_aluno').show();
                $('#nome').hide();
                $('#email').hide();
                $('#telefone').hide();
                $('#instituicao').show();
            break;
            case '2':
                $('#id_pessoa').show();
                $('#id_aluno').hide();
                $('#nome').hide();
                $('#email').hide();
                $('#telefone').hide();
                $('#instituicao').hide();
            break;
            case '3':
                $('#id_pessoa').hide();
                $('#id_aluno').hide();
                $('#nome').show();
                $('#email').show();
                $('#telefone').show();
                $('#instituicao').show();
            break;
        }
    }",
    yii\web\View::POS_HEAD,
    'my-button-handler'
);
?>
<div class="sisliga-liga-update">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tab' => $tab,
    ]) ?>

</div>
