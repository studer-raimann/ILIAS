<?php

use ILIAS\GlobalScreen\Scope\MainMenu\Collector\Renderer\Hasher;
use ILIAS\GlobalScreen\Scope\Tool\Provider\AbstractDynamicToolProvider;
use ILIAS\GlobalScreen\ScreenContext\Stack\CalledContexts;
use ILIAS\GlobalScreen\ScreenContext\Stack\ContextCollection;
use ILIAS\UI\Component\JavaScriptBindable;

/**
 * Class ilHelpGSToolProvider
 * @author Alex Killing <killing@leifos.com>
 */
class ilHelpGSToolProvider extends AbstractDynamicToolProvider
{
    const SHOW_HELP_TOOL = 'show_help_tool';
    use ilHelpDisplayed;
    use Hasher;

    /**
     * @inheritDoc
     */
    public function isInterestedInContexts() : ContextCollection
    {
        return $this->context_collection->main();
    }

    /**
     * @inheritDoc
     */
    public function getToolsForContextStack(CalledContexts $called_contexts) : array
    {
        global $DIC;

        $lng = $DIC->language();
        $lng->loadLanguageModule("help");
        $f = $DIC->ui()->factory();

        $tools = [];

        $title = $lng->txt("help");
        $icon  = $f->symbol()->icon()->standard("hlps", $title)->withIsOutlined(true);

        if ($this->showHelpTool()) {
            $iff = function ($id) {
                return $this->identification_provider->contextAwareIdentifier($id, true);
            };
            $l   = function (string $content) {
                return $this->dic->ui()->factory()->legacy($content);
            };

            $identification = $iff("help");
            $hashed = $this->hash($identification->serialize());
            $tools[]        = $this->factory->tool($identification)
                                            ->addComponentDecorator(static function (ILIAS\UI\Component\Component $c) use($hashed) : ILIAS\UI\Component\Component {
                                         if ($c instanceof JavaScriptBindable) {
                                             return $c->withAdditionalOnLoadCode(static function ($id) use ($hashed){
                                                 return "
                                                 console.log('$id');
                                                 $('body').on('open_help_slate', function(){
                                                    console.log('received open_help_slate event for tool {$hashed}');
                                                    il.UI.maincontrols.mainbar.engageTool('{$hashed}}');
                                                 });";
                                             });
                                         }
                                         return $c;
                                     })
                                     ->withInitiallyHidden(false)
                                     ->withTitle($title)
                                     ->withSymbol($icon)
                                     ->withContentWrapper(function () use ($l) {
                                         return $l($this->getHelpContent());
                                     })
                                     ->withPosition(90);
        }

        return $tools;
    }

    /**
     * help
     * @param int $ref_id
     * @return string
     */
    private function getHelpContent() : string
    {
        global $DIC;

        $ctrl     = $DIC->ctrl();
        $main_tpl = $DIC->ui()->mainTemplate();

        /** @var ilHelpGUI $help_gui */
        $help_gui = $DIC["ilHelp"];

        $help_gui->initHelp($main_tpl, $ctrl->getLinkTargetByClass("ilhelpgui", "", "", true));

        $html = "";
        if ((defined("OH_REF_ID") && OH_REF_ID > 0) || DEVMODE == 1) {
            $html = "<div class='ilHighlighted small'>Screen ID: " . $help_gui->getScreenId() . "</div>";
        }

        $html .= "<div id='ilHelpPanel'>&nbsp;</div>";

        return $html;
    }
}
