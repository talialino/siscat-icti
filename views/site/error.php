<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<section class="content">

    <div class="error-page">
        <h2 class="headline text-info"><i class="fa fa-warning text-yellow"></i></h2>

        <div class="error-content">
            <h3><?= $name ?></h3>
            <form action="/siscat/site/contact">
            
                <textarea name='error' rows='6' cols='80' class="error-msg" style="resize: none; border: none;" readonly><?=$message?></textarea>

                <div class="form-group">
                    <?= Html::submitButton('Reportar erro?', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>
            </form>
        </div>
    </div>

</section>
