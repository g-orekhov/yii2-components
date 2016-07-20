<?php

namespace backend\components\imagepreview\grid;

use backend\components\imagepreview\ImagePreviewWidget;
use Yii;

class ImagePreviewColumn extends \yii\grid\DataColumn
{
    public $attribute = 'image';
    public $filter = false;
    public $format = 'raw';
    public $size = ImagePreviewWidget::SIZE_SMALL;
    public $contentOptions = ['class' => 'image-preview-row'];

    public function init()
    {
        parent::init();
        $this->value = function ($model) {
            return ImagePreviewWidget::widget(['src' => $model->getImageUrl($model->{$this->attribute}), 'size' => $this->size]);
        };
    }
}
