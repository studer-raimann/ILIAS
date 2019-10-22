<?php

namespace ILIAS\GlobalScreen\Scope\MainMenu\Collector\Renderer;

use ILIAS\GlobalScreen\Collector\Renderer\isSupportedTrait;
use ILIAS\GlobalScreen\Scope\MainMenu\Factory\isItem;
use ILIAS\GlobalScreen\Scope\MainMenu\Factory\TopItem\TopParentItem;
use ILIAS\UI\Component\Component;

/**
 * Class TopParentItemRenderer
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class TopParentItemRenderer extends BaseTypeRenderer
{

    use SlateSessionStateCode;
    use isSupportedTrait;


    /**
     * @inheritDoc
     */
    public function getComponentForItem(isItem $item) : Component
    {
        $f = $this->ui_factory;

        /**
         * @var $item TopParentItem
         */

        $slate = $f->mainControls()->slate()->combined($item->getTitle(), $this->getStandardSymbol($item));

        if (strpos($_SERVER['SCRIPT_NAME'], 'src/GlobalScreen/Client/content.php') === false) {
            $slate = $slate->withAsyncContentURL("./src/GlobalScreen/Client/content.php?item="
                . $this->hash($item->getProviderIdentification()->serialize()));
        } else {
            $a = 1;
        }

        foreach ($item->getChildren() as $child) {
            $component = $child->getTypeInformation()->getRenderer()->getComponentForItem($child);
            if ($this->isComponentSupportedForCombinedSlate($component)) {
                $slate = $slate->withAdditionalEntry($component);
            }
        }
        $slate = $this->addOnloadCode($slate, $item);

        return $slate;
    }
}
