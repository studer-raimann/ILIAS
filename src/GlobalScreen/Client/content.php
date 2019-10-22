<?php namespace ILIAS\GlobalScreen\Client;

use ILIAS\GlobalScreen\Scope\MainMenu\Collector\Renderer\Hasher;

chdir("../../../");
require_once('./libs/composer/vendor/autoload.php');

class ContentRenderer
{

    use Hasher;


    public function run()
    {
        \ilInitialisation::initILIAS();
        global $DIC;
        $GS = $DIC->globalScreen();

        $GS->collector()->mainmenu()->collect();

        $unhash = $this->unhash($_GET['item']);
        $identification = $GS->identification()->fromSerializedIdentification($unhash);
        $item = $GS->collector()->mainmenu()->getSingleItem($identification);

        $component = $item->getTypeInformation()->getRenderer()->getComponentForItem($item);

        echo $DIC->ui()->renderer()->render($component);
    }
}

(new ContentRenderer())->run();

