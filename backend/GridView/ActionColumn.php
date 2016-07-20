<?php
namespace common\overrides\grid;

use Yii;
use yii\helpers\Html;

class ActionColumn extends \yii\grid\ActionColumn
{
    /**
     * @inheritdoc
     */
    public $template = "{view}\n\r{update}\n\r{delete}\n\r{disable}\n\r{activate}\n\r";
    
    /**
     * @inheritdoc
     */
    public $contentOptions = ['width' => 85];

    public $isActiveMethod = 'isActive';
    public $canDeleteMethod = 'canDelete';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initDefaultVisibleButtons();
    }

    /**
     * @inheritdoc
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                $link = Html::a(Yii::t('yii', '{viewIcon} View', ['viewIcon' => '<span class="glyphicon glyphicon-eye-open"></span>']), $url, $options);
                return Html::tag('div', $link, ['class' => 'view-action-wrapper']);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                $link = Html::a(Yii::t('yii', '{updateIcon} Update', ['updateIcon' => '<span class="glyphicon glyphicon-pencil"></span>']), $url, $options);
                return Html::tag('div', $link, ['class' => 'update-action-wrapper']);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                $link = Html::a(Yii::t('yii', '{deleteIcon} Delete', ['deleteIcon' => '<span class="glyphicon glyphicon-trash"></span>']), $url, $options);
                return Html::tag('div', $link, ['class' => 'delete-action-wrapper']);
            };
        }
        if (!isset($this->buttons['disable'])) {
            $this->buttons['disable'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Disable'),
                    'aria-label' => Yii::t('yii', 'Disable'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                $link = Html::a(Yii::t('yii', '{disableIcon} Disable', ['disableIcon' => '<span class="glyphicon glyphicon-unchecked"></span>']), $url, $options);
                return Html::tag('div', $link, ['class' => 'disable-action-wrapper']);
            };
        }
        if (!isset($this->buttons['activate'])) {
            $this->buttons['activate'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Activate'),
                    'aria-label' => Yii::t('yii', 'Activate'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                $link = Html::a(Yii::t('yii', '{activateIcon} Activate', ['activateIcon' => '<span class="glyphicon glyphicon-check"></span>']), $url, $options);
                return Html::tag('div', $link, ['class' => 'activate-action-wrapper']);
            };
        }
    }

    /**
     * Init default visible buttons
     * Adding disable and activate callable-s when none set by the user
     */
    protected function initDefaultVisibleButtons()
    {
        if (!isset($this->visibleButtons['delete'])) {
            $this->visibleButtons['delete'] = function ($model) {
                return method_exists($model, $this->canDeleteMethod) ? call_user_func([$model, $this->canDeleteMethod]) : true;
            };
        }
        if (!isset($this->visibleButtons['disable'])) {
            $this->visibleButtons['disable'] = function ($model) {
                return $this->checkActiveStatusHelper($model);
            };
        }
        if (!isset($this->visibleButtons['activate'])) {
            $this->visibleButtons['activate'] = function ($model) {
                return $this->checkActiveStatusHelper($model, true);
            };
        }
    }

    protected function checkActiveStatusHelper($model, $invertReturnValue = false)
    {
        // Don't show soft delete buttons if model cannot be deleted
        if (method_exists($model, $this->canDeleteMethod) && !call_user_func([$model, $this->canDeleteMethod])) {
            return false;
        }

        // Check if model is active
        if (method_exists($model, $this->isActiveMethod)) {
            $isActive = call_user_func([$model, $this->isActiveMethod]);
        } else {
            // Don't show any button if model status cannot be checked
            return false;
        }

        return $invertReturnValue ? !$isActive : $isActive;
    }
}
