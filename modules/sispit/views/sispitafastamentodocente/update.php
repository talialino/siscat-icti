<?php

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitAfastamentoDocente */
use app\assets\SispitAsset;
SispitAsset::register($this);
?>
<div class="sispit-afastamento-docente-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
