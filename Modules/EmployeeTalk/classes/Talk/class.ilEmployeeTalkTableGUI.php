<?php
declare(strict_types=1);

use ILIAS\EmployeeTalk\UI\ControlFlowCommand;

final class ilEmployeeTalkTableGUI extends ilTable2GUI
{
    /**
     * @var ilLanguage
     */
    private $language;

    public function __construct(ilEmployeeTalkMyStaffListGUI $a_parent_obj, $a_parent_cmd = "")
    {
        global $DIC;

        /**
         * @var \ILIAS\DI\Container $container
         */
        $container = $GLOBALS['DIC'];

        $container->language()->loadLanguageModule('etal');
        $this->language = $container->language();

        $this->access = ilObjEmployeeTalkAccess::getInstance();

        $this->usr_id = $DIC->http()->request()->getQueryParams()['usr_id'];

        $this->setPrefix('myst_etal_list_');
        $this->setFormName('myst_etal_list');
        $this->setId('myst_etal_list');

        parent::__construct($a_parent_obj, $a_parent_cmd, '');
        $this->setRowTemplate('tpl.list_employee_talk_row.html', "Modules/EmployeeTalk");
        $this->setFormAction($DIC->ctrl()->getFormAction($a_parent_obj));
        ;
        $this->setDefaultOrderDirection('desc');

        $this->setShowRowsSelector(true);

        $this->setEnableTitle(true);
        $this->setDisableFilterHiding(true);
        $this->setEnableNumInfo(true);
        //$this->setExternalSegmentation(false);

        //$this->setExportFormats(array(self::EXPORT_EXCEL, self::EXPORT_CSV));
        $this->addColumns();

        $this->initFilter();
    }

    public function initFilter()
    {
        $this->setFilterCols(6);
        $this->addFilterItemByMetaType('etal_title', self::FILTER_TEXT, false, $this->language->txt('title'));
        $this->addFilterItemByMetaType('etal_template', self::FILTER_TEXT, false, $this->language->txt('type'));
        $this->addFilterItemByMetaType('etal_date', self::FILTER_DATE, false, $this->language->txt('date_of_talk'));
        $this->addFilterItemByMetaType('etal_superior', self::FILTER_TEXT, false, $this->language->txt('superior'));
        $this->addFilterItemByMetaType('etal_employee', self::FILTER_TEXT, false, $this->language->txt('employee'));
        $this->addFilterItemByMetaType('etal_status', self::FILTER_SELECT, false, $this->language->txt('status'));
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
        $this->ctrl->setParameterByClass($class, "ref_id", $a_set["ref_id"]);
        $url = $this->ctrl->getLinkTargetByClass($class, ControlFlowCommand::DEFAULT);

        $this->ctrl->setParameterByClass($class, "item_ref_id", $a_set["ref_id"]);
        $deleteUrl = $this->ctrl->getLinkTargetByClass($class, ControlFlowCommand::DELETE_INDEX);
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

        //parent::fillRow($a_set);

    }

}