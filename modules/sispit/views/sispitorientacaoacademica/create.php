<?php

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitOrientacaoAcademica */
use app\assets\SispitAsset;
SispitAsset::register($this);

?>
<div class="sispit-orientacao-academica-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>