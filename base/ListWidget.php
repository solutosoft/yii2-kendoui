<?php

namespace soluto\kendoui\base;

use yii\base\InvalidConfigException;
use yii\helpers\Html;

class ListWidget extends Widget
{
    /**
     * @var string the name of the breadcrumb container tag.
     */
    public $tag = 'ul';

    /**
     * @var string the template used to render each inactive . The token `{link}`
     * will be replaced with the actual HTML link for each inactive item.
     */
    public $itemTemplate = "<li>{content}</li>\n";

    /**
     * @var array $items the option data items.
     */
    public $items = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        $id = $this->getId();

        echo $this->renderList($this->items, $id);

        $this->registerPlugin($id);
    }

    /**
     * Renders the list elements
     * @param array $items
     * @param string $id
     * @return string
     */
    protected function renderList($items, $id = null)
    {
        $list = [];

        foreach ($items as $item) {
            if (!is_array($item)) {
                $item = ['label' => $item];
            }

            if (isset($item['items'])) {
                $item['content'] = $this->renderList($item['items']);
            }

            $list[] = $this->renderItem($item);
        }

        $options = ($id !== null) ? ['id' => $id] : [];

        return Html::tag($this->tag, implode('', $list), $options);
    }

    /**
     * Renders a single item.
     * @param array $item the item to be rendered
     * @param string $template the template to be used to rendered the item. The token "{content}" will be replaced by the content.
     * @return string the rendering result
     * @throws InvalidConfigException if `$item` does not have "label" element.
     */
    protected function renderItem($item)
    {
        $template = $this->itemTemplate;

        if (array_key_exists('label', $item)) {
            $content = $item['label'];
        } else {
            throw new InvalidConfigException('The "label" element is required.');
        }

        if (isset($item['content'])) {
            $content .= $item['content'];
        }

        if (isset($content['template'])) {
            $template = $content['template'];
        }

        return strtr($template, ['{content}' => $content]);
    }
}
