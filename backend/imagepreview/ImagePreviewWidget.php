<?php

namespace backend\components\imagepreview;

use porcelanosa\magnificPopup\MagnificPopup;
use yii\base\Widget;
use yii\helpers\Html;

class ImagePreviewWidget extends Widget
{
    const SIZE_NORMAL = 'normal';
    const SIZE_SMALL = 'small';

    public $src = null;
    public $size = self::SIZE_SMALL;

    static $isFirstTime = true;

    /**
     * @inheritdoc
     */
    public function run()
    {
        ImagePreviewAsset::register($this->getView());
        $result = '';
        if (self::$isFirstTime) {
            $result = MagnificPopup::widget([
                'target' => '.content',
                'options' => [
                    'type' => 'image',
                    'closeOnContentClick' => true,
                    'delegate' => 'a.image-preview-link',
                ]
            ]);
            self::$isFirstTime = false;
        }
        $result .= Html::beginTag('a', ['href' => $this->src, 'class' => 'image-preview-link']);
        $result .= Html::img($this->src, ['class' => $this->getCssClass()]);
        $result .= Html::endTag('a');
        return $result;
    }

    /**
     * @return string
     */
    protected function getCssClass()
    {
        switch ($this->size) {
            case self::SIZE_NORMAL:
                return 'image-preview';
            case self::SIZE_SMALL:
                return 'small-image-preview';
            default:
                return 'image-preview';
        }
    }
}