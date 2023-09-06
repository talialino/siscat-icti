<?php

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitEnsinoOrientacao */
use app\assets\SispitAsset;
SispitAsset::register($this);
?>
<div class="sispit-ensino-orientacao-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
