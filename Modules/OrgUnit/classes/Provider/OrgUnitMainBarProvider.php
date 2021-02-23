<?php namespace ILIAS\OrgUnit\Provider;

use ILIAS\DI\Container;
use ILIAS\GlobalScreen\Helper\BasicAccessCheckClosures;
use ILIAS\GlobalScreen\Identification\IdentificationInterface;
use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticMainMenuProvider;
use ILIAS\MainMenu\Provider\StandardTopItemsProvider;
use ilObjOrgUnit;
use ilObjTalkTemplate;
use ilObjTalkTemplateAdministration;

/**
 * Class OrgUnitMainBarProvider
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class OrgUnitMainBarProvider extends AbstractStaticMainMenuProvider
{
    /**
     * @var IdentificationInterface $organisationIdentifier
     */
    private $organisationIdentifier;
    /**
     * @var IdentificationInterface $orgUnitIdentifier
     */
    private $orgUnitIdentifier;
    /**
     * @var IdentificationInterface $orgUnitIdentifier
     */
    private $employeeTalkTemplateIdentifier;

    public function __construct(Container $dic)
    {
        parent::__construct($dic);
        $this->organisationIdentifier = $this->if->identifier('mm_adm_org');
        $this->orgUnitIdentifier = $this->if->identifier('mm_adm_org_orgu');
        $this->employeeTalkTemplateIdentifier = $this->if->identifier('mm_adm_org_etal');
    }
    /**
     * @inheritDoc
     */
    public function getStaticTopItems() : array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getStaticSubItems() : array
    {
        $this->dic->language()->loadLanguageModule('mst');
        $this->dic->language()->loadLanguageModule('etal');

        $items         = [];
        $access_helper = BasicAccessCheckClosures::getInstance();
        $top           = StandardTopItemsProvider::getInstance()->getAdministrationIdentification();



        $title  = $this->dic->language()->txt("objs_orgu");
        $action = "ilias.php?baseClass=ilAdministrationGUI&ref_id=" . ilObjOrgUnit::getRootOrgRefId() . "&cmd=jump";
        $icon   = $this->dic->ui()->factory()->symbol()->icon()->standard('orgu', $title)
            ->withIsOutlined(true);
        $linkOrgUnit = $this->mainmenu->link($this->orgUnitIdentifier)
            ->withAlwaysAvailable(true)
            ->withAction($action)
            ->withNonAvailableReason($this->dic->ui()->factory()->legacy("{$this->dic->language()->txt('item_must_be_always_active')}"))
            ->withParent($this->organisationIdentifier)
            ->withTitle($title)
            ->withSymbol($icon)
            ->withPosition(10)
            ->withVisibilityCallable(
                $access_helper->hasAdministrationAccess(function () {
                    return (bool) $this->dic->access()->checkAccess('read', '', ilObjOrgUnit::getRootOrgRefId());
                }));

        $title  = $this->dic->language()->txt("mm_talk_template", "");
        $action = "ilias.php?baseClass=ilAdministrationGUI&ref_id=" . ilObjTalkTemplateAdministration::getRootRefId() . "&cmd=jump";
        $icon   = $this->dic->ui()->factory()->symbol()->icon()->standard('etal', $title)
            ->withIsOutlined(true);
        $linkEmployeeTalkTemplates = $this->mainmenu->link($this->employeeTalkTemplateIdentifier)
            ->withAlwaysAvailable(true)
            ->withAction($action)
            ->withNonAvailableReason($this->dic->ui()->factory()->legacy("{$this->dic->language()->txt('item_must_be_always_active')}"))
            ->withParent($this->organisationIdentifier)
            ->withTitle($title)
            ->withSymbol($icon)
            ->withPosition(20)
            ->withVisibilityCallable(
                $access_helper->hasAdministrationAccess(function () {
                    return (bool) $this->dic->access()->checkAccess('read', '', ilObjOrgUnit::getRootOrgRefId());
                }));

        $title  = $this->dic->language()->txt("mm_organisation");
        $icon   = $this->dic->ui()->factory()->symbol()->icon()->standard('org', $title)
            ->withIsOutlined(true);
        $items[] = $this->mainmenu->linkList($this->organisationIdentifier)
                                  ->withAlwaysAvailable(true)
                                  ->withNonAvailableReason($this->dic->ui()->factory()->legacy("{$this->dic->language()->txt('item_must_be_always_active')}"))
                                  ->withParent($top)
                                  ->withTitle($title)
                                  ->withSymbol($icon)
                                  ->withPosition(7)
                                  ->withLinks([$linkOrgUnit, $linkEmployeeTalkTemplates])
                                  ->withVisibilityCallable(
                                      $access_helper->hasAdministrationAccess(function () {
                                          return (bool) $this->dic->access()->checkAccess('read', '', ilObjOrgUnit::getRootOrgRefId());
                                      }));

        return $items;
    }
}
