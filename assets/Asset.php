<?php
namespace app\modules\pricer\assets;
use yii\web\View;
use yii\web\AssetBundle;

class Asset extends AssetBundle {
    public $sourcePath = '@app/modules/pricer/assets';
    public $css = ['css/pricer.css'];
    public $js = ['js/dependsOn.min.js','js/pricer.js'];
    public $publishOptions = [
        'forceCopy' => true,
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
    public $depends = [
        'yii\web\YiiAsset',
        'rmrevin\yii\fontawesome\CdnProAssetBundle'
    ];
}