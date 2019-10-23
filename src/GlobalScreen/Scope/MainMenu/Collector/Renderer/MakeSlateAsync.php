<?php

namespace ILIAS\GlobalScreen\Scope\MainMenu\Collector\Renderer;

use ILIAS\GlobalScreen\Scope\MainMenu\Factory\supportsAsynchronousLoading;
use ILIAS\UI\Component\MainControls\Slate\Slate;

/**
 * Trait MakeSlateAsync
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
trait MakeSlateAsync
{

    use Hasher;


    /**
     * @param Slate                       $slate
     * @param supportsAsynchronousLoading $item
     *
     * @return Slate
     */
    protected function addAsyncLoadingCode(Slate $slate, supportsAsynchronousLoading $item) : Slate
    {
        if ($item->supportsAsynchronousLoading() === false) {
            return $slate;
        }

        $engage_signal = $slate->getEngageSignal();

        $slate = $slate->withAdditionalOnLoadCode(function ($id) use ($engage_signal) {
            return "console.log('Engage-Signal: {$engage_signal->getId()}')";
        });

        return $slate;
    }
}
