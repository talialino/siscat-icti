<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitAtividadeComplementar */

?>
<div class="sispit-atividade-complementar">
    <div class="panel panel-primary">
        <div class="panel-heading">    
            <h3 class="panel-title"><?= Html::encode('Atividade Complementar') ?></h3>
        </div>

        <?= $this->render($plano->isRitAvailable() ? '_rit' : '_pit', [
            'model' => $model,
        ]) ?>

    </div>
</div>
