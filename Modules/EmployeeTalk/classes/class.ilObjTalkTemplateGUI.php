<?php
declare(strict_types=1);

/**
 * Class ilObjTalkTemplateGUI
 *
 * @author            : Oskar Truffer <ns@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy ilObjTalkTemplateGUI: ilAdministrationGUI, ilObjTalkTemplateAdministrationGUI
 * @ilCtrl_Calls      ilObjTalkTemplateGUI: ilCommonActionDispatcherGUI
 * @ilCtrl_Calls      ilObjTalkTemplateGUI: ilColumnGUI, ilObjectCopyGUI, ilUserTableGUI
 * @ilCtrl_Calls      ilObjTalkTemplateGUI: ilPermissionGUI
 * @ilCtrl_Calls      ilObjTalkTemplateGUI: ilInfoScreenGUI
 */
final class ilObjTalkTemplateGUI extends ilContainerGUI
{
    public function __construct()
    {
        global $DIC;
        $lng = $DIC['lng'];
        parent::__construct([], $_GET["ref_id"], true, false);


        $this->type = 'talt';

        $lng->loadLanguageModule("talt");
        $lng->loadLanguageModule("meta");
    }

    public function executeCommand()
    {
        $cmd = $this->ctrl->getCmd();
        $next_class = $this->ctrl->getNextClass($this);


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

        return true;
    }

    public function viewObject()
    {
        $this->tabs_gui->activateTab('view_content');
    }

    protected function initEditCustomForm(ilPropertyFormGUI $a_form)
    {
        $online = new ilCheckboxInputGUI($this->lng->txt('rep_activation_online'), 'activation_online');
        $online->setInfo($this->lng->txt('talt_activation_online_info'));
        $a_form->addItem($online);

        parent::initEditCustomForm($a_form);
    }

    protected function getEditFormCustomValues(array &$a_values)
    {
        $a_values['activation_online'] = !boolval($this->object->getOfflineStatus());

        parent::getEditFormCustomValues($a_values); // TODO: Change the autogenerated stub
    }

    public function addExternalEditFormCustom(ilPropertyFormGUI $a_form)
    {
        $header = new ilFormSectionHeaderGUI();
        $header->setParentForm($a_form);
        $header->setTitle("Metadata");

        $md = $this->initMetaDataForm($a_form);
        $md->parse();

        parent::addExternalEditFormCustom($a_form);
    }

    protected function updateCustom(ilPropertyFormGUI $a_form)
    {
        $this->object->setOfflineStatus(!boolval($a_form->getInput('activation_online')));

        $md = $this->initMetaDataForm($a_form);
        $md->saveSelection();

        parent::updateCustom($a_form);
    }

    /**
     * infoScreen redirect handling of ObjListGUI
     */
    public function infoScreenObject(): void {
        $this->ctrl->redirectByClass(strtolower(ilInfoScreenGUI::class), "showSummary");
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

    protected function initCreationForms($a_new_type): array
    {
        return [
            self::CFORM_NEW => $this->initCreateForm($a_new_type)
        ];
    }

    /**
     * @param ilTabsGUI $tabs_gui
     */
    public function getAdminTabs()
    {
        $this->getTabs();
    }

    private function initMetaDataForm(ilPropertyFormGUI $form): ilAdvancedMDRecordGUI {
        $md = new ilAdvancedMDRecordGUI(ilAdvancedMDRecordGUI::MODE_REC_SELECTION, $this->object->getType(), $this->object->getId(), "etal");
        $md->setRefId($this->object->getRefId());
        $md->setPropertyForm($form);
        return $md;
    }

}