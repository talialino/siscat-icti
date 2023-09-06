<?php
/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitPesquisaExtensao */
use app\assets\SispitAsset;
SispitAsset::register($this);
?>
<div class="sispit-pesquisa-extensao-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
