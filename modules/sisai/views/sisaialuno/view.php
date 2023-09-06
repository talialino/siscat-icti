<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\assets\SisaiAsset;
SisaiAsset::register($this);

?>
<div class="sisai-aluno-view">

    <?= DetailView::widget([
        'model' => $model,
        'panel' => ['type' => 'primary', 'heading' => $this->title],
        'enableEditMode' => false,
        'attributes' => [
            'nome',
            'matricula',
            'id_user',
            'email:email',
            'colegiado',
            'ativo:boolean',
        ],
    ]) ?>

</div>
