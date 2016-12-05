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
     * @param array $options
     * @return void
     */
    public function registerPlugin($options)
    {
        $id = $this->getId();
        $options = Json::encode($options);

        $view = $this->getView();
        $view->registerJs("jQuery('#$id').{$this->pluginName}($options);");
    }

}
