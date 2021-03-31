<?php
declare(strict_types=1);

use ILIAS\EmployeeTalk\UI\ControlFlowCommand;

/**
 * Class ilTalkTemplateListGUI
 *
 * @author            Nicolas Schäfli <ns@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy ilObjEmployeeTalkSeriesListGUI: ilEmployeeTalkGUI
 * @ilCtrl_Calls      ilObjEmployeeTalkSeriesListGUI: ilFormPropertyDispatchGUI
 */
final class ilObjEmployeeTalkSeriesListGUI extends ilObjectListGUI
{
    /**
     * initialisation
     */
    public function init()
    {
        parent::init();

        $this->static_link_enabled = true;
        $this->delete_enabled = true;
        $this->cut_enabled = false;
        $this->info_screen_enabled = false;
        $this->copy_enabled = false;
        $this->subscribe_enabled = false;
        $this->link_enabled = false;

        $this->type = ilObjEmployeeTalkSeries::TYPE;
        $this->gui_class_name = strtolower(self::class);
        $this->commands = ilObjEmployeeTalkSeriesAccess::_getCommands();
    }

    /**
     * no timing commands needed in orgunits.
     */
    public function insertTimingsCommand(): void
    {

    }

    /**
     * no social commands needed in orgunits.
     * @param bool $a_header_actions
     */
    public function insertCommonSocialCommands($a_header_actions = false): void
    {

    }

    /**
     * @param string $a_cmd
     *
     * @return string
     */
    public function getCommandLink($a_cmd)
    {
        $this->ctrl->setParameterByClass(strtolower(ilObjEmployeeTalkSeriesGUI::class), "ref_id", $this->ref_id);
        return $this->ctrl->getLinkTargetByClass(strtolower(ilObjEmployeeTalkSeriesGUI::class), $a_cmd);
    }
}