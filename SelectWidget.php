<?php

namespace soluto\kendoui;

use yii\helpers\Html;

class SelectWidget extends InputWidget
{
    /**
     * @var array $items the option data items. The array keys are option values, and the array values
     * are the corresponding option labels.
     */
    public $items = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeDropDownList($this->model, $this->attribute, $this->items, $this->options);
        } else {
            echo Html::dropDownList($this->name, $this->value, $this->items, $this->options);
        }

        $this->registerPlugin($this->options['id']);
    }
}
