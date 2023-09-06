<?php

use yii\helpers\Html;
use app\assets\SisapeAsset;
SisapeAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeProjeto */

$this->title = $this->title = Yii::t('app', 'Atualizar Projeto');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisape')), 'url' => ['/'.strtolower(Yii::t('app', 'sisape'))]];
$this->params['breadcrumbs'][] = ['label' => 'Meus Projetos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Visualizar Projeto', 'url' => ['view', 'id' => $model->id_projeto]];
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
                $('#vinculo').show();
            break;
            case '2':
                $('#id_pessoa').show();
                $('#id_aluno').hide();
                $('#nome').hide();
                $('#email').hide();
                $('#telefone').hide();
                $('#vinculo').hide();
            break;
            case '3':
                $('#id_pessoa').hide();
                $('#id_aluno').hide();
                $('#nome').show();
                $('#email').show();
                $('#telefone').show();
                $('#vinculo').show();
            break;
        }
    }",
    yii\web\View::POS_HEAD,
    'my-button-handler'
);
?>
<div class="sisape-projeto-update">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tab' => $tab,
    ]) ?>

</div>
