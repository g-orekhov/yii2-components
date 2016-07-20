<?php
namespace common\overrides\grid;

use Yii;

class StatusColumn extends \yii\grid\DataColumn
{
    public $attribute = 'status';
    public $filter = null;
    public $value = 'statusText';
    public $contentOptions = ['width' => '120px'];
    public $getFilterMethod = 'getStatusTexts';

    public function init()
    {
        parent::init();
        if ($this->grid->filterModel && method_exists($this->grid->filterModel, $this->getFilterMethod)) {
            $this->filter = call_user_func([$this->grid->filterModel, $this->getFilterMethod]);
        }
    }
}
