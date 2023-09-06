<?php

namespace app\assets;

use yii\web\AssetBundle;

class SispitAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/sispit.css?v=4',
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
