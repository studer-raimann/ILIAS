<?php

/* Copyright (c) 1998-2021 ILIAS open source, GPLv3, see LICENSE */

/**
 * Upload dir files table
 *
 * @author Alexander Killing <killing@leifos.de>
 */
class ilUploadDirFilesTableGUI extends ilTable2GUI
{
    /**
     * @var ilAccessHandler
     */
    protected $access;


    /**
     * Constructor
     */
    public function __construct($a_parent_obj, $a_parent_cmd, $a_files)
    {
        global $DIC;

        $this->ctrl = $DIC->ctrl();
        $this->lng = $DIC->language();
        $this->access = $DIC->access();
        $ilCtrl = $DIC->ctrl();
        $lng = $DIC->language();
        $ilAccess = $DIC->access();
        $lng = $DIC->language();

        $mset = new ilSetting("mobs");

        $import_directory_factory = new ilImportDirectoryFactory();
        $mob_import_directory = $import_directory_factory->getInstanceForComponent(ilImportDirectoryFactory::TYPE_MOB);
        $this->upload_dir = $mob_import_directory->getAbsolutePath();

        //var_dump($_POST);
        parent::__construct($a_parent_obj, $a_parent_cmd);
        $this->setData($this->getFiles($a_files));
        $this->setTitle($lng->txt("mep_upload_dir_files"));
        $this->setLimit(9999);

        $this->addColumn("", "", "1", true);
        $this->addColumn($this->lng->txt("mep_file"));
        $this->setOpenFormTag(false);

        $this->setEnableHeader(true);
        $this->setFormAction($ilCtrl->getFormAction($a_parent_obj));
        $this->setRowTemplate("tpl.upload_dir_files_row.html", "Modules/MediaPool");
        $this->disable("footer");
        $this->setEnableTitle(true);
        $this->setSelectAllCheckbox("file[]");

        $this->addMultiCommand("createMediaFromUploadDir", $lng->txt("mep_create_media_files"));
        //$this->addCommandButton("", $lng->txt(""));
    }

    /**
     * Get files
     */
    public function getFiles($a_files)
    {
        $files = array();
        foreach ($a_files as $f) {
            if (is_file($this->upload_dir . "/" . $f)) {
                $files[] = $f;
            } elseif (is_dir($this->upload_dir . "/" . $f)) {
                $dir = ilUtil::getDir($this->upload_dir . "/" . $f, true);
                foreach ($dir as $d) {
                    if ($d["type"] == "file") {
                        $files[] = $f . $d["subdir"] . "/" . $d["entry"];
                    }
                }
            }
        }

        return $files;
    }

    /**
     * Fill table row
     */
    protected function fillRow($a_set)
    {
        $lng = $this->lng;

        $this->tpl->setVariable("TXT_FILE", $a_set);
        $this->tpl->setVariable("VAL_FILE", $a_set);
    }
}
