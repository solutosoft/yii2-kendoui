<?php

namespace soluto\kendoui\ui;

use soluto\kendoui\base\InputWidget;
use yii\helpers\Html;

class Editor extends InputWidget
{
    /**
     * @inheritdoc
     */
    public $pluginName = 'kendoEditor';

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }

        $this->registerPlugin($this->options['id']);
    }
}
