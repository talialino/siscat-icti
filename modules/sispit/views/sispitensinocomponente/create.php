<?php
/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitEnsinoComponente */
use app\assets\SispitAsset;
SispitAsset::register($this);
?>
<div class="sispit-ensino-componente-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
