<?php

namespace soluto\kendoui\ui;

use soluto\kendoui\base\SelectWidget;

class MultiSelect extends SelectWidget
{
    /**
     * @inheritdoc
     */
    public $pluginName = 'kendoMultiSelect';

    /**
     * @inheritdoc
     */
    protected function nullValue()
    {
        return [];
    }
}
