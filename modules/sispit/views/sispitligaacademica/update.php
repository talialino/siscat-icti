<?php
/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitLigaAcademica */
use app\assets\SispitAsset;
SispitAsset::register($this);
?>
<div class="sispit-liga-academica-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
