<?php

namespace soluto\kendoui;

use yii\helpers\Html;
use yii\helpers\Json;

class SelectWidget extends InputWidget
{
    /**
     * @var array $items the option data items. The array keys are option values, and the array values
     * are the corresponding option labels.
     */
    public $items = [];

    /**
     * @var string the initial selected element
     */
    public $selected = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        $options = $this->options;

        if ($this->hasModel()) {
            echo Html::activeDropDownList($this->model, $this->attribute, $this->items, $options);
        } else {
            echo Html::dropDownList($this->name, $this->value, $this->items, $options);
        }

        $value = isset($options['value']) ? $options['value'] : null;
        if ($value === null) {
            $value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        }

        $id = $options['id'];
        $this->registerPlugin($id);

        $plugin = "$('#$id').data('$this->pluginName')";
        $view = $this->getView();

        if (!empty($this->selected) && array_filter(array_values($this->selected))) {
            $selected = Json::encode($this->selected);
            $view->registerJs("$plugin.dataSource.add($selected);");
        }

        if ($value !== null) {
            $view->registerJs("$plugin.value($value);");
            $view->registerJs("$plugin.trigger('change');");
        }
    }
}
