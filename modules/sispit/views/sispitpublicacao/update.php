<?php

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitPublicacao */
use app\assets\SispitAsset;
SispitAsset::register($this);
?>
<div class="sispit-publicacao-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
