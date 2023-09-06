<?php

use yii\helpers\Html;
use app\assets\SisaiAsset;
SisaiAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\AdicionarComponentesForm */

$this->title = 'Avaliação Concluída';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisai')), 'url' => ['/'.strtolower(Yii::t('app', 'sisai'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-avaliacao-final">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    
        <div class="sisai-final-tecnico box box-info">
            <div class="mensagem-sucesso">
            <p>Obrigada por sua avaliação!</p>
            <p>Sua contribuição é muito importante para o processo avaliativo do nosso
            instituto, o que nos permite estar sempre aperfeiçoando nossos serviços.</p>
            </div>
        </div>

</div>