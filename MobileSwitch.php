<?php

namespace soluto\kendoui;

use yii\helpers\Html;

class MobileSwitch extends InputWidget
{
    /**
     * @inheritdoc
     */
    public $pluginName = 'kendoMobileSwitch';

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeCheckbox($this->model, $this->attribute, $this->options);
        } else {
            echo Html::checkbox($this->name, $this->value, $this->options);
        }

        $this->registerPlugin($this->options['id']);
    }
}
