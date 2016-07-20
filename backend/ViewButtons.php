<?php

namespace backend\components;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class ViewButtons extends Widget
{
    public $template = "{update}\n{delete}\n{soft-delete}\n{index}\n";

    public $showUpdate = true;
    public $showDelete = true;
    public $showIndex = true;
    public $softDelete = true;
    public $isActiveMethod = 'isActive';

    public $buttons = [];

    public $model = null;

    public function init()
    {
        parent::init();
        if ($this->model === null) {
            throw new \Exception('Model property is not defined.');
        }
        $this->initDefaultButtons($this->model);
    }

    protected function initDefaultButtons($model)
    {
        if ($this->showUpdate) {
            $this->buttons['update'] = Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        }
        if ($this->showDelete) {
            $this->buttons['delete'] = Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]);
        }
        if ($this->softDelete) {
            if (call_user_func([$this->model, $this->isActiveMethod])) {
                $this->buttons['soft-delete'] = Html::a(Yii::t('app', 'Disable'), ['disable', 'id' => $model->id], ['class' => 'btn btn-warning']);
            } else {
                $this->buttons['soft-delete'] = Html::a(Yii::t('app', 'Activate'), ['activate', 'id' => $model->id], ['class' => 'btn btn-success']);
            }
        }
        if ($this->showIndex) {
            $this->buttons['index'] = Html::a(Yii::t('app', 'Show all'), ['index'], ['class' => 'btn btn-info']);
        }
    }

    public function run()
    {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) {
            $name = $matches[1];
            if (isset($this->buttons[$name])) {
                return $this->buttons[$name];
            }
        }, $this->template);
    }
}