<?php

namespace soluto\kendoui;

use yii\helpers\Html;

class Upload extends InputWidget
{
    /**
     * @inheritdoc
     */
    public $pluginName = 'kendoUpload';

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeFileInput($this->model, $this->attribute, $this->options);
        } else {
            echo Html::fileInput($this->name, $this->value, $this->options);
        }

        $this->registerPlugin();
    }
}
