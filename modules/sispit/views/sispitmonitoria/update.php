<?php

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitMonitoria */
use app\assets\SispitAsset;
SispitAsset::register($this);
?>
<div class="sispit-monitoria-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
