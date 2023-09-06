<?php

namespace app\assets;

use yii\web\AssetBundle;

class SisextAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/sisext.css?v=2',
    ];
    public $js = [
        //'js/modal.js?v=1',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
