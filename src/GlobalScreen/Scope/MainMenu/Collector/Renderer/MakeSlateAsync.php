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

        $serialize = $item->getProviderIdentification()->serialize();
        $hash = $this->hash($serialize);
        $url = "./src/GlobalScreen/Client/content.php?item=" . $hash;

        $replace_signal = $slate->getReplaceSignal()->withAsyncRenderUrl($url);

        // $slate = $slate->appendOnEngage($replace_signal);
        //
        $slate = $slate->withAdditionalOnLoadCode(function ($id) use ($engage_signal, $replace_signal) {
            return "$('#$id').on('$engage_signal', function (event, signalData) {
                  console.log('MakeSlateAsync');
                  return event;
            });";
        });

        /*
         *  return "$('#$id').on('click', function(event) {
							window.location = '{$action}';
							return false;
					});";
         */

        return $slate;
    }
}
