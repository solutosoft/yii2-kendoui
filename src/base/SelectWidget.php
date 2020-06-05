<?php

namespace soluto\kendoui\base;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

class SelectWidget extends InputWidget
{
    /**
     * @var array $items the option data items. The array keys are option values, and the array values
     * are the corresponding option labels.
     */
    public $items = [];

    /**
     * @var array the initial selected item
     *
     * ```php
     * 'selected' => $model->category
     *
     * or
     *
     * 'selected' => [
     *     'id' => $model->category_id,
     *     'description' => ArrayHelper($model, 'category.description')
     * ]
     * ```
     */
    public $selected = [];

    /**
     * @var string the javascript template function used to initialize selected item.
     * For use this resource is necessary to add a global javascript function with following template declaration
     *
     * For example:
     *
     * ```js
     * function kendoInitSelected(e, value, selected) {
     *     var results = e.response[e.sender.options.schema.data],
     *         contains = jQuery.grep(results, function(item){return item.id == value});
     *
     *     if (contains.length == 0) {
     *         results.unshift(selected);
     *     }
     * }
     * ```
     */
    public $initSelectedTemplate = 'kendoInitSelected(e, {value}, {selected});';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $options = $this->options;
        $id = $options['id'];

        if ($this->hasModel()) {
            echo Html::activeDropDownList($this->model, $this->attribute, $this->items, $options);
        } else {
            echo Html::dropDownList($this->name, $this->value, $this->items, $options);
        }

        if (isset($options['value'])) {
            $value = $options['value'];
        } else {
            $value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        }

        if ($value === null) {
            $value = '';
        }

        $this->pluginOptions['value'] = $value;

        if (!empty($this->selected) && $value) {
            $selected = Json::encode($this->selected);
            $template = strtr($this->initSelectedTemplate,['{value}' => $value, '{selected}' => $selected]);

            $this->pluginOptions = ArrayHelper::merge([
                'dataSource' => [
                    'requestEnd' => new JsExpression("function(e){ $template }")
                ]
            ], $this->pluginOptions);
        }

        $this->registerPlugin($id);
    }
}
