<?php

namespace ILIAS\GlobalScreen\Scope\MainMenu\Collector\Renderer;

use ILIAS\GlobalScreen\Collector\Renderer\ComponentDecoratorApplierTrait;
use ILIAS\GlobalScreen\Scope\MainMenu\Factory\isItem;
use ILIAS\UI\Component\Component;

/**
 * Class RepositoryLinkItemRenderer
 */
class RepositoryLinkItemRenderer extends BaseTypeRenderer
{
    use ComponentDecoratorApplierTrait;

    /**
     * @inheritDoc
     */
    public function getComponentWithContent(isItem $item) : Component
    {
        return $this->ui_factory->link()->bulky($this->getStandardSymbol($item), $item->getTitle(), $this->getURI($item->getAction()));
    }
}
