<?php
declare(strict_types=1);

use ILIAS\EmployeeTalk\UI\ControlFlowCommand;
use ILIAS\EmployeeTalk\UI\ControlFlowCommandHandler;

final class ilEmployeeTalkTableGUI extends ilTable2GUI
{
    const STATUS_ALL = 0;
    const STATUS_PENDING = 1;
    const STATUS_COMPLETED = 2;

    /**
     * @var ilLanguage
     */
    private $language;
    /**
     * @var ilObjUser $currentUser
     */
    private $currentUser;

    public function __construct(ControlFlowCommandHandler $a_parent_obj, $a_parent_cmd = "")
    {
        /**
         * @var \ILIAS\DI\Container $container
         */
        $container = $GLOBALS['DIC'];

        $this->language = $container->language();
        $this->currentUser = $container->user();
        $this->language->loadLanguageModule('etal');
        $this->language->loadLanguageModule('orgu');

        $this->setPrefix('myst_etal_list_');
        $this->setFormName('myst_etal_list');
        $this->setId('myst_etal_list');

        parent::__construct($a_parent_obj, $a_parent_cmd, '');
        $this->setRowTemplate('tpl.list_employee_talk_row.html', "Modules/EmployeeTalk");
        $this->setFormAction($container->ctrl()->getFormAction($a_parent_obj));
        ;
        $this->setDefaultOrderDirection('desc');

        $this->setShowRowsSelector(true);


        $this->setEnableTitle(true);
        $this->setDisableFilterHiding(true);
        $this->setEnableNumInfo(true);
        //$this->setExternalSorting(false);
        //$this->setExternalSegmentation(false);
        $this->setExternalSegmentation(true);

        //$this->setExportFormats(array(self::EXPORT_EXCEL, self::EXPORT_CSV));
        $this->addColumns();

        $this->initFilter();
        $this->determineLimit();
        $this->determineOffsetAndOrder();
    }

    public function initFilter()
    {
        $this->setFilterCols(6);
        $this->addFilterItemByMetaType('etal_title', self::FILTER_TEXT, false, $this->language->txt('title'));
        $this->addFilterItemByMetaType('etal_template', self::FILTER_TEXT, false, $this->language->txt('type'));

        /**
         * @var ilDateTimeInputGUI $dateSelectInput
         */
        $dateSelectInput = $this->addFilterItemByMetaType('etal_date', self::FILTER_DATE, false, $this->language->txt('date_of_talk'));

        // Filter throws a null pointer error if not set
        if ($dateSelectInput->getDate() === null) {
            $dateSelectInput->setDate(new ilDateTime());
        }
        $this->addFilterItemByMetaType('etal_superior', self::FILTER_TEXT, false, $this->language->txt('superior'));
        $this->addFilterItemByMetaType('etal_employee', self::FILTER_TEXT, false, $this->language->txt('employee'));
        /**
         * @var ilSelectInputGUI $ilSelectInput
         */
        $ilSelectInput = $this->addFilterItemByMetaType('etal_status', self::FILTER_SELECT, false, $this->language->txt('status'));
        $ilSelectInput->setOptions([
            self::STATUS_ALL => $this->language->txt('etal_status_all'),
            self::STATUS_PENDING => $this->language->txt('etal_status_pending'),
            self::STATUS_COMPLETED => $this->language->txt('etal_status_completed')
        ]);
    }

    private function addColumns(): void {
        $this->addColumn(
            $this->language->txt('title'),
            "etal_title",
            "auto"
        );
        $this->addColumn(
            $this->language->txt('type'),
            "etal_template",
            "auto"
        );
        $this->addColumn(
            $this->language->txt('date_of_talk'),
            "etal_date",
            "auto"
        );
        $this->addColumn(
            $this->language->txt('superior'),
            "etal_superior",
            "auto"
        );
        $this->addColumn(
            $this->language->txt('employee'),
            "etal_employee",
            "auto"
        );
        $this->addColumn(
            $this->language->txt('status'),
            "etal_status",
            "auto"
        );
        $this->addColumn(
            $this->language->txt('action'),
            "",
            "auto"
        );

        $this->setDefaultFilterVisiblity(true);
        $this->setDefaultOrderField("etal_date");
    }

    protected function fillRow($a_set): void
    {
        $class = strtolower(ilObjEmployeeTalkGUI::class);
        $classPath = [
            strtolower(ilDashboardGUI::class),
            strtolower(ilMyStaffGUI::class),
            strtolower(ilEmployeeTalkMyStaffListGUI::class),
            $class
        ];
        $this->ctrl->setParameterByClass($class, "ref_id", $a_set["ref_id"]);
        $url = $this->ctrl->getLinkTargetByClass($classPath, ControlFlowCommand::DEFAULT);

        $this->ctrl->setParameterByClass($class, "item_ref_id", $a_set["ref_id"]);
        $deleteUrl = $this->ctrl->getLinkTargetByClass($classPath, ControlFlowCommand::DELETE_INDEX);
        $deleteButton = ilLinkButton::getInstance();
        $deleteButton->setUrl($deleteUrl);
        $deleteButton->setCaption('delete');
        $this->tpl->setVariable("HREF_ETAL_TITLE", $url);
        $this->tpl->setVariable("VAL_ETAL_TITLE", $a_set['etal_title']);
        $this->tpl->setVariable("VAL_ETAL_TEMPLATE", $a_set['etal_template']);
        $this->tpl->setVariable("VAL_ETAL_DATE", $a_set['etal_date']);
        $this->tpl->setVariable("VAL_ETAL_SUPERIOR", $a_set['etal_superior']);
        $this->tpl->setVariable("VAL_ETAL_EMPLOYEE", $a_set['etal_employee']);
        $this->tpl->setVariable("VAL_ETAL_STATUS", $a_set['etal_status']);
        $this->tpl->setVariable("ACTIONS", $deleteButton->render());
    }

    function setTalkData(array $talks): void {
        $filter = $this->getCurrentState()['filter_values'];

        $data = [];
        foreach ($talks as $val) {
            if (!ilObject::_hasUntrashedReference($val->getObjectId())) {
                continue;
            }

            if ($filter['etal_employee'] !== false) {
                $filterUser = ilObjUser::getUserIdByLogin($filter['etal_employee']);
                if ($val->getEmployee() !== $filterUser) {
                    continue;
                }
            }

            $refIds = ilObjEmployeeTalk::_getAllReferences($val->getObjectId());
            $talk = new ilObjEmployeeTalk(array_pop($refIds), true);
            $parent = $talk->getParent();
            $talkData = $talk->getData();
            $employeeName = $this->language->txt('etal_unknown_username');
            $superiorName = $this->language->txt('etal_unknown_username');
            $ownerId = $talk->getOwner();
            if (ilObjUser::_exists($talk->getOwner())) {
                $superiorName = ilObjUser::_lookupLogin($ownerId);
            }
            if (ilObjUser::_exists($talkData->getEmployee())) {
                $employeeName = ilObjUser::_lookupLogin($talkData->getEmployee());
            }

            if ($filter['etal_superior'] !== false) {
                $filterUser = ilObjUser::getUserIdByLogin($filter['etal_superior']);
                if (intval($talk->getOwner()) !== $filterUser) {
                    continue;
                }
            }

            if ($filter['etal_title'] !== false) {
                if (strpos($talk->getTitle(), $filter['etal_title']) === false) {
                    continue;
                }
            }

            if ($filter['etal_template'] !== false) {
                if (strpos($parent->getTitle(), $filter['etal_template']) === false) {
                    continue;
                }
            }

            if ($filter['etal_date'] !== false && $filter['etal_date'] !== null) {
                $filterDate = new ilDateTime($filter['etal_date'], IL_CAL_DATE);
                if (
                !ilDateTime::_equals($filterDate, $val->getStartDate(), IL_CAL_DAY)
                ) {
                    continue;
                }
            }

            if ($filter['etal_status'] !== false && intval($filter['etal_status'] !== 0)) {
                $filterCompleted = intval($filter['etal_status']) === ilEmployeeTalkTableGUI::STATUS_COMPLETED;
                if ($filterCompleted && !$val->isCompleted()) {
                    continue;
                }

                if (!$filterCompleted && $val->isCompleted()) {
                    continue;
                }
            }

            $data[] = [
                "ref_id" => $talk->getRefId(),
                "etal_title" => $talk->getTitle(),
                "etal_template" => $parent->getTitle(),
                "etal_date" => $talkData->getStartDate()->get(
                    IL_CAL_DATETIME,
                    $this->currentUser->getTimeFormat(),
                    $this->currentUser->getTimeZone()
                ),
                "etal_superior" => $superiorName,
                "etal_employee" => $employeeName,
                "etal_status" => $talkData->isCompleted() ? $this->language->txt('etal_status_completed') : $this->language->txt('etal_status_pending')
            ];
        }

        $offset = intval($this->getOffset());
        $limit = intval($this->getLimit()) + 1;

        $this->setMaxCount(count($talks));

        $dataSlice = array_slice($data, $offset, $limit, true);
        $this->setData($dataSlice);
    }

}