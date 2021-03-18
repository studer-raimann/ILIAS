<?php
declare(strict_types=1);

use ILIAS\EmployeeTalk\UI\ControlFlowCommand;
use OrgUnit\User\ilOrgUnitUser;
use ILIAS\Modules\EmployeeTalk\Talk\DAO\EmployeeTalk;

/**
 * Class ilObjEmployeeTalkGUI
 *
 * @ilCtrl_IsCalledBy ilObjEmployeeTalkGUI: ilEmployeeTalkMyStaffListGUI
 * @ilCtrl_Calls      ilObjEmployeeTalkGUI: ilCommonActionDispatcherGUI
 * @ilCtrl_Calls      ilObjEmployeeTalkGUI: ilRepositorySearchGUI
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

    /**
     * @var ilPropertyFormGUI $form
     */
    private $form;

    public function __construct()
    {
        parent::__construct([], $_GET["ref_id"], true, false);

        $this->container = $GLOBALS["DIC"];

        $this->container->language()->loadLanguageModule('mst');
        $this->container->language()->loadLanguageModule('trac');
        $this->container->language()->loadLanguageModule('etal');
        $this->container->language()->loadLanguageModule('dateplaner');

        $this->type = 'etal';

        $this->setReturnLocation("save", strtolower(ilEmployeeTalkMyStaffListGUI::class));

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
            case strtolower(ilRepositorySearchGUI::class):
                $repo = new ilRepositorySearchGUI();
                //$repo->addUserAccessFilterCallable(function () {
                //    $orgUnitUser = ilOrgUnitUser::getInstanceById($this->container->user()->getId());
                //    $orgUnitUser->addPositions()                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ;
                //});
                $this->container->ctrl()->forwardCommand($repo);
                break;
            case strtolower(ilEmployeeTalkAppointmentGUI::class):
                $appointmentGUI = new ilEmployeeTalkAppointmentGUI(
                    $this->tpl,
                    $this->lng,
                    $this->ctrl,
                    $this->container->tabs()
                );
                $this->container->ctrl()->forwardCommand($appointmentGUI);
                break;
            default:
                return parent::executeCommand();
        }

        //$this->container->ui()->mainTemplate()->printToStdout();
        return true;
    }

    /**
     * Redirect to etalk mystaff list instead of parent which is not accessible by most users.
     *
     * @param ilObject $a_new_object
     */
    protected function afterSave(ilObject $a_new_object)
    {
        /**
         * @var ilObjEmployeeTalk $newObject
         */
        $newObject = $a_new_object;
        $data = $newObject->getData();

        $location = $this->form->getInput('etal_location');
        $employee = $this->form->getInput('etal_employee');
        ['tgl' => $tgl] = $this->form->getInput('etal_event');
        $recurrence = $this->form->getInput('etal_recurrence');

        $data->setLocation($location ?? '');
        $data->setEmployee(ilObjUser::getUserIdByLogin($employee));

        $data->setAllDay(boolval(intval($tgl)));

        /**
         * @var ilDateDurationInputGUI $dateTimeInput
         */
        $dateTimeInput = $this->form->getItemByPostVar('etal_event');
        ['start' => $start, 'end' => $end] = $dateTimeInput->getValue();
        $startDate = new ilDateTime($start, IL_CAL_UNIX, ilTimeZone::UTC);
        $data->setStartDate($startDate);
        $endDate = new ilDateTime($end, IL_CAL_UNIX, ilTimeZone::UTC);
        $data->setEndDate($endDate);

        $newObject->setData($data);
        $newObject->update();

        //TODO: Fix double redirect bug ...
        ilUtil::sendSuccess($this->lng->txt("object_added"), true);
        $this->ctrl->redirectByClass(strtolower(ilEmployeeTalkMyStaffListGUI::class), ControlFlowCommand::DEFAULT, "", false);
    }

    protected function initCreateForm($a_new_type)
    {
        // Init dom events or ui will break on page load
        ilYuiUtil::initDomEvent();

        $form = new ilPropertyFormGUI();
        $form->setTarget("_top");
        $form->setFormAction($this->ctrl->getFormAction($this, "save"));
        $form->setTitle($this->lng->txt($a_new_type . "_new"));

        // title
        $ti = new ilTextInputGUI($this->lng->txt("title"), "title");
        $ti->setSize(min(40, ilObject::TITLE_LENGTH));
        $ti->setMaxLength(ilObject::TITLE_LENGTH);
        $ti->setRequired(true);
        $form->addItem($ti);

        // description
        $ta = new ilTextAreaInputGUI($this->lng->txt("description"), "desc");
        $ta->setCols(40);
        $ta->setRows(2);
        $form->addItem($ta);

        // Start & End Date
        $this->tpl->addJavaScript('./Services/Form/js/date_duration.js');
        $dur = new ilDateDurationInputGUI($this->lng->txt('cal_fullday'), 'etal_event');
        $dur->setRequired(true);
        $dur->enableToggleFullTime(
            $this->lng->txt('cal_fullday_title'), false
        );
        $dur->setShowTime(true);
        $form->addItem($dur);

        // Recurrence
        $cal = new ilRecurrenceInputGUI("Calender", "etal_recurrence");
        $event = new ilEventRecurrence();
        //$event->setRecurrence(ilEventRecurrence::REC_EXCLUSION);
        //$event->setFrequenceType(ilEventRecurrence::FREQ_DAILY);
        $cal->allowUnlimitedRecurrences(false);
        $cal->setRecurrence($event);
        //$cal->
        $form->addItem($cal);

        //Location
        $location = new ilTextInputGUI("Location", "etal_location");
        $location->setMaxLength(200);
        $form->addItem($location);

        //Employee
        $login = new ilTextInputGUI($this->lng->txt("employee"), "etal_employee");
        $login->setDataSource($this->ctrl->getLinkTargetByClass([
            strtolower(self::class),
            strtolower(ilRepositorySearchGUI::class)
        ], 'doUserAutoComplete', '', true));
        $form->addItem($login);

        $form = $this->initDidacticTemplate($form);

        $form->addCommandButton("save", $this->lng->txt($a_new_type . "_add"));
        $form->addCommandButton("cancel", $this->lng->txt("cancel"));

        $this->form = $form;

        return $form;
    }

    protected function initCreationForms($a_new_type): array
    {
        return [
            self::CFORM_NEW => $this->initCreateForm($a_new_type)
        ];
    }

    public function addExternalEditFormCustom(ilPropertyFormGUI $a_form)
    {
        /**
         * @var EmployeeTalk $data
         */
        $data = $this->object->getData();

        $location = new ilTextInputGUI("Location", "etal_location");
        $location->setMaxLength(200);
        $location->setValue($data->getLocation());
        $a_form->addItem($location);

        $login = new ilTextInputGUI($this->lng->txt("employee"), "etal_employee");
        $login->setDataSource($this->ctrl->getLinkTargetByClass([
            strtolower(self::class),
            strtolower(ilRepositorySearchGUI::class)
        ], 'doUserAutoComplete', '', true));

        $login->setRequired(true);
        $login->setSize(50);
        $login->setValue(ilObjUser::_lookupLogin($data->getEmployee()));
        $a_form->addItem($login);

        $completed = new ilCheckboxInputGUI('Completed', 'etal_completed');
        $completed->setChecked($data->isCompleted());
        $a_form->addItem($completed);

        $header = new ilFormSectionHeaderGUI();
        $header->setTitle("Date");
        $a_form->addItem($header);

        $this->container->ctrl()->setParameterByClass(strtolower(ilEmployeeTalkAppointmentGUI::class), 'ref_id', $this->ref_id);

        $btnChangeThis = ilLinkButton::getInstance();
        $btnChangeThis->setCaption("change_date_of_talk");
        $editMode = '&' . ilEmployeeTalkAppointmentGUI::EDIT_MODE . '=' . ilEmployeeTalkAppointmentGUI::EDIT_MODE_APPOINTMENT;
        $btnChangeThis->setUrl($this->ctrl->getLinkTargetByClass(strtolower(ilEmployeeTalkAppointmentGUI::class), ControlFlowCommand::UPDATE_INDEX) . $editMode);
        $this->toolbar->addButtonInstance($btnChangeThis);

        $btnChangeAll = ilLinkButton::getInstance();
        $btnChangeAll->setCaption("change_date_of_series");
        $editMode = '&' . ilEmployeeTalkAppointmentGUI::EDIT_MODE . '=' . ilEmployeeTalkAppointmentGUI::EDIT_MODE_SERIES;
        $btnChangeAll->setUrl($this->ctrl->getLinkTargetByClass(strtolower(ilEmployeeTalkAppointmentGUI::class), ControlFlowCommand::UPDATE_INDEX) . $editMode);
        $this->toolbar->addButtonInstance($btnChangeAll);

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

        $location = $a_form->getInput('etal_location');
        $completed = boolval(
            intval($a_form->getInput('etal_completed'))
        );
        $employee = $a_form->getInput('etal_employee');

        /**
         * @var EmployeeTalk $data
         */
        $data = $this->object->getData();
        $data->setCompleted($completed);
        $data->setLocation($location ?? '');
        $data->setEmployee(ilObjUser::getUserIdByLogin($employee));
        $this->object->setData($data);

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