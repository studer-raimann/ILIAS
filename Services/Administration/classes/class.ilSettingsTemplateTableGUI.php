<?php

/* Copyright (c) 1998-2021 ILIAS open source, GPLv3, see LICENSE */

/**
 * Settings templates table
 *
 * @author Alex Killing <alex.killing@gmx.de>
 */
class ilSettingsTemplateTableGUI extends ilTable2GUI
{
    /**
     * @var ilCtrl
     */
    protected $ctrl;

    /**
     * @var ilAccessHandler
     */
    protected $access;
    /**
     * @var \ILIAS\DI\Container
     */
    protected $dic;
    /**
     * @var
     */
    protected $rbacsystem;


    /**
     * Constructor
     */
    public function __construct($a_parent_obj, $a_parent_cmd, $a_type)
    {
        global $DIC;

        $this->dic = $DIC;
        $this->ctrl = $this->dic->ctrl();
        $this->lng = $this->dic->language();
        $this->access = $this->dic->access();
        $this->rbacsystem = $this->dic->rbac()->system();
        $ilCtrl = $this->dic->ctrl();
        $lng = $this->dic->language();
        $ilAccess = $this->dic->access();
        $lng = $this->dic->language();

        $this->setId("admsettemp" . $a_type);

        parent::__construct($a_parent_obj, $a_parent_cmd);

        $this->setData(ilSettingsTemplate::getAllSettingsTemplates($a_type, true));
        $this->setTitle($lng->txt("adm_settings_templates") . " - " .
            $lng->txt("obj_" . $a_type));

        $this->addColumn("", "", "1", true);
        $this->addColumn($this->lng->txt("title"), "title");
        $this->addColumn($this->lng->txt("description"));
        $this->addColumn($this->lng->txt("actions"));

        $this->setFormAction($ilCtrl->getFormAction($a_parent_obj));
        $this->setRowTemplate(
            "tpl.settings_template_row.html",
            "Services/Administration"
        );

        if ($this->rbacsystem->checkAccess('write', $_GET['ref_id'])) {
            $this->addMultiCommand("confirmSettingsTemplateDeletion", $lng->txt("delete"));
            //$this->addCommandButton("", $lng->txt(""));
        }
    }

    /**
     * Fill table row
     */
    protected function fillRow($a_set)
    {
        $lng = $this->lng;
        $ilCtrl = $this->ctrl;

        $ilCtrl->setParameter($this->parent_obj, "templ_id", $a_set["id"]);
        $this->tpl->setVariable("VAL_ID", $a_set["id"]);
        // begin-patch lok
        $this->tpl->setVariable("VAL_TITLE", ilSettingsTemplate::translate($a_set["title"]));
        $this->tpl->setVariable("VAL_DESCRIPTION", ilSettingsTemplate::translate($a_set["description"]));
        if ($this->rbacsystem->checkAccess('write', $_GET['ref_id'])) {
            // end-patch lok
            $this->tpl->setVariable("TXT_EDIT", $lng->txt("edit"));
            $this->tpl->setVariable(
                "HREF_EDIT",
                $ilCtrl->getLinkTarget($this->parent_obj, "editSettingsTemplate")
            );
        }
        $ilCtrl->setParameter($this->parent_obj, "templ_id", "");
    }
}
