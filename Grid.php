<?php

namespace soluto\kendoui;

use yii\helpers\html;

class Grid extends Widget
{
    /**
     * @inheritdoc
     */
    public $pluginName = 'kendoGrid';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $id = $this->getId();

        echo Html::tag('div', '', ['id' => $id]);

        $this->registerPlugin($this->pluginOptions);
    }

}
