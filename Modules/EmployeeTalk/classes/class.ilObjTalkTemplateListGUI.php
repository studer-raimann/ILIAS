<?php
declare(strict_types=1);

final class ilObjTalkTemplateListGUI extends ilObjectListGUI
{
    /**
     * initialisation
     */
    public function init()
    {
        parent::init();

        $this->static_link_enabled = true;
        $this->delete_enabled = true;
        $this->cut_enabled = true;
        $this->info_screen_enabled = true;
        $this->copy_enabled = false;
        $this->subscribe_enabled = false;
        $this->link_enabled = false;

        $this->type = "talt";
        $this->gui_class_name = strtolower(ilObjTalkTemplateGUI::class);
        $this->commands = ilObjTalkTemplateAccess::_getCommands();
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

    public function getTitle()
    {
        return parent::getTitle(); // TODO: Change the autogenerated stub
    }

    /**
     * @param string $a_cmd
     *
     * @return string
     */
    public function getCommandLink($a_cmd)
    {
        $this->ctrl->setParameterByClass(strtolower(ilObjTalkTemplateGUI::class), "ref_id", $this->ref_id);
        return $this->ctrl->getLinkTargetByClass(strtolower(ilObjTalkTemplateGUI::class), $a_cmd);
    }


}