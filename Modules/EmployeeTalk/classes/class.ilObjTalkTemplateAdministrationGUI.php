<?php
declare(strict_types=1);

/**
 * Class ilObjTalkTemplateAdministrationGUI GUI class
 * @author            : Nicolas Schaefli <ns@studer-raimann.ch>
 * @ilCtrl_IsCalledBy ilObjTalkTemplateAdministrationGUI: ilAdministrationGUI
 * @ilCtrl_Calls      ilObjTalkTemplateAdministrationGUI: ilCommonActionDispatcherGUI
 * @ilCtrl_Calls      ilObjTalkTemplateAdministrationGUI: ilColumnGUI, ilObjectCopyGUI, ilUserTableGUI
 * @ilCtrl_Calls      ilObjTalkTemplateAdministrationGUI: ilPermissionGUI
 * @ilCtrl_Calls      ilObjTalkTemplateAdministrationGUI: ilInfoScreenGUI
 * @ilCtrl_Calls      ilObjTalkTemplateAdministrationGUI: ilObjTalkTemplateGUI
 * @ilCtrl_Calls      ilObjTalkTemplateAdministrationGUI: ilObjEmployeeTalkSeriesGUI
 */
final class ilObjTalkTemplateAdministrationGUI extends ilContainerGUI
{

    public function __construct()
    {
        global $DIC;
        $lng = $DIC['lng'];
        parent::__construct([], $_GET["ref_id"], true, false);

        $this->type = 'tala';

        $lng->loadLanguageModule("tala");
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
            case strtolower(ilObjTalkTemplateGUI::class):
                $ilTalkTemplateGUI = new ilObjTalkTemplateGUI();
                $this->ctrl->forwardCommand($ilTalkTemplateGUI);
                break;
            default:
                $result = parent::executeCommand();
                $this->tabs_gui->removeSubTab("page_editor");
                $this->tabs_gui->activateTab('view_content');
                return $result;
        }

        return true;
    }

    /**
     * called by prepare output
     */
    public function setTitleAndDescription()
    {
        # all possible create permissions
        parent::setTitleAndDescription();
        $this->tpl->setTitle($this->lng->txt("objs_tala"));
        $this->tpl->setDescription($this->lng->txt("objs_tala"));

        $this->tpl->setTitleIcon("", $this->lng->txt("obj_" . $this->object->getType()));
    }

    public function showPossibleSubObjects()
    {
        $gui = new ilObjectAddNewItemGUI($this->object->getRefId());
        $gui->setMode(ilObjectDefinition::MODE_ADMINISTRATION);
        $gui->setCreationUrl($this->ctrl->getLinkTargetByClass(strtolower(ilObjTalkTemplateGUI::class), 'create'));
        $gui->setDisabledObjectTypes([ilObjEmployeeTalkSeries::TYPE]);
        $gui->render();
    }

    public function viewObject()
    {
        if (!$this->rbacsystem->checkAccess("read", $_GET["ref_id"])) {
            if ($this->rbacsystem->checkAccess("visible", $_GET["ref_id"])) {
                ilUtil::sendFailure($this->lng->txt("msg_no_perm_read"));
                $this->ctrl->redirectByClass(strtolower(ilInfoScreenGUI::class), '');
            }

            $this->ilias->raiseError($this->lng->txt("msg_no_perm_read"), $this->ilias->error_obj->WARNING);
        }

        parent::renderObject();
    }

    /**
     * Filter the view by talk templates because the talk series objects are also children of the talk template administration.
     *
     * @return ilContainerContentGUI
     */
    public function getContentGUI(): ilContainerContentGUI
    {
        return new ilContainerByTypeContentGUI($this, new ilContainerUserFilter(['std_' . ilContainerFilterField::STD_FIELD_OBJECT_TYPE => ilObjTalkTemplate::TYPE]));
    }

    public function getTabs(): void
    {
        $read_access_ref_id = $this->rbacsystem->checkAccess('visible,read', $this->object->getRefId());
        if ($read_access_ref_id) {
            $this->tabs_gui->addTab('view_content', $this->lng->txt("content"), $this->ctrl->getLinkTarget($this, "view"));
            $this->tabs_gui->addTab("info_short", "Info", $this->ctrl->getLinkTargetByClass(strtolower(ilInfoScreenGUI::class), "showSummary"));
        }
        //$this->tabs_gui->activateTab('view_content');
        parent::getTabs();
    }

    /**
     * @param ilTabsGUI $tabs_gui
     */
    public function getAdminTabs()
    {
        $this->getTabs();
    }

}