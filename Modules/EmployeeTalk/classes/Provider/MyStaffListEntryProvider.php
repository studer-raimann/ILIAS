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
use ilDashboardGUI;
use ilMyStaffGUI;
use ilEmployeeTalkMyStaffListGUI;
use ILIAS\EmployeeTalk\UI\ControlFlowCommand;
use ILIAS\MyStaff\ilMyStaffAccess;
use ilSetting;

final class MyStaffListEntryProvider extends AbstractStaticMainMenuProvider
{
    /**
     * @var IdentificationInterface $organisationIdentifier
     */
    private $organisationIdentifier;
    /**
     * @var IdentificationInterface $orgUnitIdentifier
     */
    private $employeeTalkTemplateIdentifier;
    /**
     * @var ilSetting $settings
     */
    private $settings;

    public function __construct(Container $dic)
    {
        parent::__construct($dic);
        $this->organisationIdentifier = StandardTopItemsProvider::getInstance()->getOrganisationIdentification();
        $this->employeeTalkTemplateIdentifier = $this->if->identifier('mm_adm_org_etal');
        $this->settings = $dic->settings();
    }

    public function getStaticTopItems() : array
    {
        return [];
    }

    public function getStaticSubItems() : array
    {
        $this->dic->language()->loadLanguageModule('etal');
        $items = [];

        $title = $this->dic->language()->txt("mm_org_etal");
        $action = "ilias.php?baseClass=ilAdministrationGUI&ref_id=" . ilObjOrgUnit::getRootOrgRefId() . "&cmd=jump";
        $icon = $this->dic->ui()->factory()->symbol()->icon()->standard('etal', $title)
                          ->withIsOutlined(false);

        $items[] = $this->mainmenu->link($this->employeeTalkTemplateIdentifier)
                                  ->withAlwaysAvailable(false)
                                  ->withAction($this->dic->ctrl()->getLinkTargetByClass([
                                      ilDashboardGUI::class,
                                      ilMyStaffGUI::class,
                                      ilEmployeeTalkMyStaffListGUI::class,
                                  ], ControlFlowCommand::INDEX))
                                  ->withAvailableCallable(
                                      function () {
                                          return boolval($this->settings->get('enable_my_staff'));
                                      }
                                  )
                                  ->withNonAvailableReason($this->dic->ui()->factory()->legacy("{$this->dic->language()->txt('item_must_be_always_active')}"))
                                  ->withParent($this->organisationIdentifier)
                                  ->withTitle($title)
                                  ->withSymbol($icon)
                                  ->withPosition(60)
                                  ->withVisibilityCallable(
                                      function () {
                                          return ilMyStaffAccess::getInstance()->hasCurrentUserAccessToMyStaff();
                                      });

        return $items;
    }

    /**
     * @inheritDoc
     */
    public function getProviderNameForPresentation() : string
    {
        return "Modules/EmployeeTalk";
    }

}