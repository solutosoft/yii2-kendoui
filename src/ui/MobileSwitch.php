<?php

namespace soluto\kendoui\ui;

use soluto\kendoui\base\InputWidget;
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
        $options = array_merge($this->options, ['label' => false]);

        if ($this->hasModel()) {
            echo Html::activeCheckbox($this->model, $this->attribute, $options);
        } else {
            echo Html::checkbox($this->name, $this->value, $options);
        }

        $this->registerPlugin($this->options['id']);
    }
}
