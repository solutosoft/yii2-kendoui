<?php

namespace soluto\kendoui\base;

use yii\helpers\Html;

class InputWidget extends \yii\widgets\InputWidget
{
    use PluginTrait;

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textInput($this->name, $this->value, $this->options);
        }

        $this->registerPlugin($this->options['id']);
    }
}
