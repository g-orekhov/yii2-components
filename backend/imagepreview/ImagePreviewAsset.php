<?php

namespace backend\components\imagepreview;

use yii\web\AssetBundle;

class ImagePreviewAsset extends AssetBundle
{
    public $sourcePath = '@backend/components/imagepreview/src';

    public $css = [
        'css/imagePreview.css'
    ];
    
    public $depends = [
        'backend\assets\AppAsset',
    ];
}