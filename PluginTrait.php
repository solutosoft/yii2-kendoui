<?php

namespace soluto\kendoui;

use yii\helpers\Json;

trait PluginTrait
{
    /**
     * @var string the plugin name
     */
    public $pluginName;

    /**
     * @var array the plugin name
     */
    public $pluginOptions = [];

    /**
     * Register plugin options
     * @param string $id
     * @return void
     */
    public function registerPlugin($id)
    {
        $options = $this->pluginOptions ? Json::encode($this->pluginOptions) : '';

        $view = $this->getView();
        $view->registerJs("jQuery('#$id').{$this->pluginName}($options);");
    }

}
