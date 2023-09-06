<?php

namespace app\assets;

use yii\web\AssetBundle;

class SiscoaeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/siscoae.css',
    ];
    public $js = [
        'js/modal.js?v=1',
        'js/tooltip.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
