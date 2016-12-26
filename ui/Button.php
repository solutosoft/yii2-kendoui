<?php

namespace soluto\kendoui\ui;

use soluto\kendoui\base\Widget;
use yii\helpers\Html;

class Button extends Widget
{
    /**
     * @inheritdoc
     */
    public $pluginName = 'kendoButton';

    /**
     * @var string $content the content enclosed within the button tag.
     */
    public $content = 'Button';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $id = $this->getId();

        echo Html::button($this->content, ['id' => $id]);

        $this->registerPlugin($id);
    }
}
