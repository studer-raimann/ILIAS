<?php
declare(strict_types=1);

/**
 * Class ilObjEmployeeTalkGUI
 *
 * @ilCtrl_IsCalledBy ilObjEmployeeTalkGUI: ilEmployeeTalkMyStaffListGUI
 * @ilCtrl_Calls      ilObjEmployeeTalkGUI: ilCommonActionDispatcherGUI
 * @ilCtrl_Calls      ilObjEmployeeTalkGUI: ilColumnGUI, ilObjectCopyGUI, ilUserTableGUI
 * @ilCtrl_Calls      ilObjEmployeeTalkGUI: ilPermissionGUI
 * @ilCtrl_Calls      ilObjEmployeeTalkGUI: ilInfoScreenGUI
 */
final class ilObjEmployeeTalkGUI extends ilObjectGUI
{
    /**
     * @var \ILIAS\DI\Container $container
     */
    private $container;

    public function __construct()
    {
        parent::__construct([], $_GET["ref_id"], true, false);

        $this->container = $GLOBALS["DIC"];

        $this->container->language()->loadLanguageModule('mst');
        $this->container->language()->loadLanguageModule('trac');
        $this->container->language()->loadLanguageModule('etal');
        $this->container->language()->loadLanguageModule('dateplaner');

        $this->type = 'etal';

        // get the standard template
        //$this->container->ui()->mainTemplate()->loadStandardTemplate();
        $this->container->ui()->mainTemplate()->setTitle($this->container->language()->txt('mst_my_staff'));
    }

    public function executeCommand() : bool
    {
        // determine next class in the call structure
        $next_class = $this->container->ctrl()->getNextClass($this);

        switch($next_class) {
            case 'ilpermissiongui':
                parent::prepareOutput();
                $this->tabs_gui->activateTab('perm_settings');
                $ilPermissionGUI = new ilPermissionGUI($this);
                $this->ctrl->forwardCommand($ilPermissionGUI);
                break;
            case 'ilinfoscreengui':
                parent::prepareOutput();
                $this->tabs_gui->activateTab('info_short');
                $ilInfoScreenGUI = new ilInfoScreenGUI($this);
                $this->ctrl->forwardCommand($ilInfoScreenGUI);
                break;
            default:
                return parent::executeCommand();
        }

        //$this->container->ui()->mainTemplate()->printToStdout();
        return true;
    }

    protected function initCreationForms($a_new_type): array
    {
        return [
            self::CFORM_NEW => $this->initCreateForm($a_new_type)
        ];
    }

    public function addExternalEditFormCustom(ilPropertyFormGUI $a_form)
    {
        //$cal = new ilRecurrenceInputGUI("Calender", "etal_cal");
        //$a_form->addItem($cal);

        $this->tpl->addJavaScript('./Services/Form/js/date_duration.js');
        $dur = new ilDateDurationInputGUI($this->lng->txt('cal_fullday'), 'event');
        $dur->setRequired(true);
        $dur->enableToggleFullTime(
            $this->lng->txt('cal_fullday_title'), true
        );
        $dur->setShowTime(true);
        //$dur->setStart($this->app->getStart());
        //$dur->setEnd($this->app->getEnd());
        $a_form->addItem($dur);

        $header = new ilFormSectionHeaderGUI();
        $header->setParentForm($a_form);
        $header->setTitle("Metadata");

        $md = $this->initMetaDataForm($a_form);
        $md->parse();



        parent::addExternalEditFormCustom($a_form);
    }

    protected function updateCustom(ilPropertyFormGUI $a_form)
    {
        /**
         * @var ilObjTalkTemplate $template
         */
        $template = $this->object->getParent();

        $md = $this->initMetaDataForm($a_form);
        $md->parse();
        $md->importEditFormPostValues();
        $md->writeEditForm($template->getId(), $this->object->getId());
        //$md->saveSelection();

        parent::updateCustom($a_form);
    }

    public function viewObject()
    {
        $this->tabs_gui->activateTab('view_content');
    }

    public function getTabs(): void
    {
        $read_access_ref_id = $this->rbacsystem->checkAccess('visible,read', $this->object->getRefId());
        if ($read_access_ref_id) {
            $this->tabs_gui->addTab('view_content', $this->lng->txt("content"), $this->ctrl->getLinkTarget($this, "view"));
            $this->tabs_gui->addTab("info_short", "Info", $this->ctrl->getLinkTargetByClass(strtolower(ilInfoScreenGUI::class), "showSummary"));
        }

        if ($this->rbacsystem->checkAccess('write', '', $this->object->getRefId())) {
            $this->tabs_gui->addTab('settings', $this->lng->txt("settings"), $this->ctrl->getLinkTarget($this, "edit"));
        }

        parent::getTabs();
    }

    /**
     * @param ilTabsGUI $tabs_gui
     */
    public function getAdminTabs()
    {
        $this->getTabs();

        if ($this->checkPermissionBool("edit_permission")) {
            $this->tabs_gui->addTab(
                'perm_settings',
                $this->lng->txt('perm_settings'),
                $this->ctrl->getLinkTargetByClass(
                    [
                        get_class($this),
                        'ilpermissiongui'
                    ],
                    'perm'
                )
            );
        }
    }

    private function initMetaDataForm(ilPropertyFormGUI $form): ilAdvancedMDRecordGUI {
        /**
         * @var ilObjTalkTemplate $template
         */
        $template = $this->object->getParent();
        $md = new ilAdvancedMDRecordGUI(ilAdvancedMDRecordGUI::MODE_EDITOR, $template->getType(), $template->getId(), $this->object->getType(), $this->object->getId());
        //$md->setRefId($template->getRefId());
        $md->setPropertyForm($form);
        return $md;
    }
}