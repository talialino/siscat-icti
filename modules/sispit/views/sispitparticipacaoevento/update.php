<?php

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitParticipacaoEvento */
use app\assets\SispitAsset;
SispitAsset::register($this);
?>
<div class="sispit-participacao-evento-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
