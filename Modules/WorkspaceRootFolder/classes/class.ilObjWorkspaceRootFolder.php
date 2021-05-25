<?php

/* Copyright (c) 1998-2021 ILIAS open source, GPLv3, see LICENSE */

/**
 * Class ilObjWorkspaceRootFolder
 *
 * @author Stefan Meyer <meyer@leifos.com>
 */
class ilObjWorkspaceRootFolder extends ilObjWorkspaceFolder
{

    /**
     * Constructor
     */
    public function __construct($a_id = 0, $a_reference = true)
    {
        global $DIC;
        parent::__construct($a_id, $a_reference);

        $this->db = $DIC->database();
    }

    public function initType()
    {
        $this->type = "wsrt";
    }

    /**
    * get all translations from this category
    *
    * @access	public
    * @return	array
    */
    public function getTranslations()
    {
        $ilDB = $this->db;

        $q = "SELECT * FROM object_translation WHERE obj_id = " .
            $ilDB->quote($this->getId(), 'integer') . " ORDER BY lang_default DESC";
        $r = $ilDB->query($q);

        $num = 0;

        $data["Fobject"] = array();
        while ($row = $r->fetchRow(ilDBConstants::FETCHMODE_OBJECT)) {
            $data["Fobject"][$num] = array("title" => $row->title,
                                          "desc" => $row->description,
                                          "lang" => $row->lang_code
                                          );
            $num++;
        }

        // first entry is always the default language
        $data["default_language"] = 0;

        return $data ? $data : array();
    }

    // remove all Translations of current category
    public function removeTranslations()
    {
        $ilDB = $this->db;

        $query = "DELETE FROM object_translation WHERE obj_id= " .
            $ilDB->quote($this->getId(), 'integer');
        $res = $ilDB->manipulate($query);
    }

    // add a new translation to current category
    public function addTranslation($a_title, $a_desc, $a_lang, $a_lang_default)
    {
        $ilDB = $this->db;

        if (empty($a_title)) {
            $a_title = "NO TITLE";
        }

        $query = "INSERT INTO object_translation " .
             "(obj_id,title,description,lang_code,lang_default) " .
             "VALUES " .
             "(" . $ilDB->quote($this->getId(), 'integer') . "," .
             $ilDB->quote($a_title, 'text') . "," .
             $ilDB->quote($a_desc, 'text') . "," .
             $ilDB->quote($a_lang, 'text') . "," .
             $ilDB->quote($a_lang_default, 'integer') . ")";
        $res = $ilDB->manipulate($query);
        return true;
    }
} // END class.ObjRootFolder
