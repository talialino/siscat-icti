<?php

use yii\helpers\Html;
use app\assets\SisaiAsset;
SisaiAsset::register($this);

?>
<div class="sisai-aluno-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
