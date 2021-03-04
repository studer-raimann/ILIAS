<?php
declare(strict_types=1);

use ILIAS\EmployeeTalk\UI\ControlFlowCommandHandler;
use ILIAS\EmployeeTalk\UI\ControlFlowCommand;

/**
 * Class ilEmployeeTalkMyStaffListGUI
 *
 * @ilCtrl_IsCalledBy ilEmployeeTalkMyStaffListGUI: ilMyStaffGUI
 */
final class ilEmployeeTalkMyStaffListGUI implements ControlFlowCommandHandler
{
    /**
     * @var \ILIAS\DI\UIServices $ui
     */
    private $ui;
    /**
     * @var ilLanguage
     */
    private $language;

    /**
     * @var ilTabsGUI
     */
    private $tabs;
    /**
     * @var ilToolbarGUI
     */
    private $toolbar;
    /**
     * @var ilCtrl $controlFlow
     */
    private $controlFlow;

    public function __construct()
    {
        /**
         * @var \ILIAS\DI\Container $container
         */
        $container = $GLOBALS['DIC'];

        $container->language()->loadLanguageModule('etal');
        $container->language()->loadLanguageModule('orgu');
        $this->language = $container->language();

        $this->tabs = $container->tabs();
        $this->ui = $container->ui();
        $this->controlFlow = $container->ctrl();
        $this->ui->mainTemplate()->setTitle($container->language()->txt('mm_org_etal'));
        $this->toolbar = $container->toolbar();
    }

    public function executeCommand() : bool
    {
        $nextClass = $this->controlFlow->getNextClass();
        switch ($nextClass) {
            case strtolower(ilObjEmployeeTalkGUI::class):
                $gui = new ilObjEmployeeTalkGUI();
                return $this->controlFlow->forwardCommand($gui);
            default:
                return $this->view();
        }
    }

    private function view(): bool {
        $this->loadActionBar();
        $this->loadTabs();
        $this->ui->mainTemplate()->setContent($this->loadTable()->getHTML());
        return true;
    }

    private function loadTabs(): void {
        $this->tabs->addTab("view_content", "Content", "#");
        $this->tabs->activateTab("view_content");
        //$this->tabs->addTab("placeholder", "", "#");
        $this->tabs->setForcePresentationOfSingleTab(true);
    }

    private function loadActionBar(): void {
        $gl = new ilGroupedListGUI();
        $gl->setAsDropDown(true, false);

        $templates = new CallbackFilterIterator(
            new ArrayIterator(ilObject::_getObjectsByType("talt")),
            function($item) {
                return $item['offline'] === "0" || $item['offline'] === null;
            }
        );

        foreach ($templates as $item) {
            $type = $item["type"];

            $path = ilObject::_getIcon('', 'tiny', $type);
            $icon = ($path != "")
                ? ilUtil::img($path, "") . " "
                : "";

            $base_url = $this->controlFlow->getLinkTargetByClass(strtolower(ilObjEmployeeTalkGUI::class), ControlFlowCommand::CREATE);
            $url = $this->controlFlow->appendRequestTokenParameterString($base_url . "&new_type=etal");
            $refId = ilObject::_getAllReferences(intval($item['obj_id']));

            // Templates only have one ref id
            $url .= "&ref_id=" . array_pop($refId);

            $ttip = ilHelp::getObjCreationTooltipText("etal");

            $gl->addEntry(
                $icon . $item["title"],
                $url,
                "_top",
                "",
                "",
                $type,
                $ttip,
                "bottom center",
                "top center",
                false
            );
        }

        $adv = new ilAdvancedSelectionListGUI();
        $adv->setListTitle($this->language->txt("etal_add_new_item"));
        //$gl->getHTML();
        $adv->setGroupedList($gl);
        $adv->setStyle(ilAdvancedSelectionListGUI::STYLE_EMPH);
        //$this->toolbar->addDropDown($this->language->txt("cntr_add_new_item"), $adv->getHTML());
        $this->ui->mainTemplate()->setVariable("SELECT_OBJTYPE_REPOS", $adv->getHTML());
    }

    private function loadTable(): ilEmployeeTalkTableGUI {
        $table = new ilEmployeeTalkTableGUI($this, ControlFlowCommand::DEFAULT);

        /**
         * @var ilObjEmployeeTalk[] $talks
         */
        $talks = ilObject::_getObjectsByType("etal");
        $data = [];
        foreach ($talks as $key => $val) {
            $refIds = ilObjEmployeeTalk::_getAllReferences($val["obj_id"]);
            $talk = new ilObjEmployeeTalk(array_pop($refIds), true);
            $parent = $talk->getParent();
            $data[] = [
                "ref_id" => $talk->getRefId(),
                "etal_title" => $talk->getTitle(),
                "etal_template" => $parent->getTitle(),
                "etal_date" => "C",
                "etal_superior" => "D",
                "etal_employee" => "E",
                "etal_status" => "F",
                "action" => "none"
            ];
        }
        $table->setData($data);
        //$table->setData([["A", "B", "C", "D", "E", "F"]]);

        return $table;
    }
}