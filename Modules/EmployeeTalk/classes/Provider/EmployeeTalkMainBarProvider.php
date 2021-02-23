<?php
declare(strict_types=1);

namespace ILIAS\EmployeeTalk\Provider;

use ILIAS\DI\Container;
use ILIAS\GlobalScreen\Helper\BasicAccessCheckClosures;
use ILIAS\GlobalScreen\Identification\IdentificationInterface;
use ILIAS\GlobalScreen\Scope\MainMenu\Factory\isItem;
use ILIAS\GlobalScreen\Scope\MainMenu\Factory\TopItem\TopParentItem;
use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticMainMenuProvider;
use ILIAS\MainMenu\Provider\StandardTopItemsProvider;
use ilObjOrgUnit;

final class EmployeeTalkMainBarProvider extends AbstractStaticMainMenuProvider
{
    /**
     * @var IdentificationInterface $organisationIdentifier
     */
    private $organisationIdentifier;
    /**
     * @var IdentificationInterface $orgUnitIdentifier
     */
    private $employeeTalkTemplateIdentifier;

    public function __construct(Container $dic)
    {
        parent::__construct($dic);
        $this->organisationIdentifier = $this->if->identifier('mm_adm_org');
        $this->employeeTalkTemplateIdentifier = $this->if->identifier('mm_adm_org_etal');
    }

    public function getStaticTopItems() : array
    {
        return [];
    }

    public function getStaticSubItems() : array
    {
        $this->dic->language()->loadLanguageModule('mst');
        $items = [];
        $access_helper = BasicAccessCheckClosures::getInstance();

        $title = $this->dic->language()->txt("mm_adm_org_etal");
        $action = "ilias.php?baseClass=ilAdministrationGUI&ref_id=" . ilObjOrgUnit::getRootOrgRefId() . "&cmd=jump";
        $icon = $this->dic->ui()->factory()->symbol()->icon()->standard('orgu', $title)
                          ->withIsOutlined(true);

        $items[] = $this->mainmenu->link($this->employeeTalkTemplateIdentifier)
                                  ->withAlwaysAvailable(true)
                                  ->withAction($action)
                                  ->withNonAvailableReason($this->dic->ui()->factory()->legacy("{$this->dic->language()->txt('item_must_be_always_active')}"))
                                  ->withParent($this->organisationIdentifier)
                                  ->withTitle($title)
                                  ->withSymbol($icon)
                                  ->withPosition(20)
                                  ->withVisibilityCallable(
                                      $access_helper->hasAdministrationAccess(function () {
                                          return (bool) $this->dic->access()->checkAccess('read', '',
                                              ilObjOrgUnit::getRootOrgRefId());
                                      }));

        return [];
    }

    /**
     * @inheritDoc
     */
    public function getProviderNameForPresentation() : string
    {
        return "EmployeeTalk";
    }

}