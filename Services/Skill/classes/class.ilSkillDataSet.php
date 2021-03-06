<?php

/* Copyright (c) 1998-2020 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Skill Data set class
 *
 * This class implements the following entities:
 * - skmg: Skill management top entity (no data, only dependecies)
 * - skl_subtree: Skill subtree (includes data of skl_tree, skl_tree_node and skl_templ_ref)
 * - skl_templ_subtree: Skill template subtree (includes data of skl_tree and skl_tree_node)
 * - skl_level: Skill levels
 * - skl_prof: skill profiles (different mode)
 *
 * @author Alex Killing <alex.killing@gmx.de>
 */
class ilSkillDataSet extends ilDataSet
{
    public const MODE_SKILLS = "";
    public const MODE_PROFILES = "prof";

    /**
     * @var ilSkillTree
     */
    protected $skill_tree;

    /**
     * @var int
     */
    protected $skill_tree_root_id;

    /**
     * @var int
     */
    protected $init_top_order_nr;

    /**
     * @var int
     */
    protected $init_templ_top_order_nr;

    protected $init_order_nr;
    protected $selected_nodes = [];
    protected $selected_profiles = [];
    protected $mode = "";

    /**
     * Constructor
     */
    public function __construct()
    {
        global $DIC;

        $this->db = $DIC->database();
        parent::__construct();
        $this->skill_tree = new ilSkillTree();
        $this->skill_tree_root_id = $this->skill_tree->readRootId();

        $this->init_top_order_nr = $this->skill_tree->getMaxOrderNr($this->skill_tree_root_id);
        $this->init_templ_top_order_nr = $this->skill_tree->getMaxOrderNr($this->skill_tree_root_id, true);
    }

    /**
     * Set mode
     *
     * @param string $a_val mode
     */
    public function setMode($a_val)
    {
        $this->mode = $a_val;
    }

    /**
     * Get mode
     *
     * @return string mode
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set export selected nodes
     *
     * @param array $a_val array of int
     */
    public function setSelectedNodes($a_val)
    {
        $this->selected_nodes = $a_val;
    }

    /**
     * Get export selected nodes
     *
     * @return array array of int
     */
    public function getSelectedNodes()
    {
        return $this->selected_nodes;
    }

    /**
     * Set selected profiles
     *
     * @param array $a_val array of int (profile ids)
     */
    public function setSelectedProfiles($a_val)
    {
        $this->selected_profiles = $a_val;
    }

    /**
     * Get selected profiles
     *
     * @return array array of int (profile ids)
     */
    public function getSelectedProfiles()
    {
        return $this->selected_profiles;
    }

    /**
     * Get supported versions
     *
     * @return array of version strings
     */
    public function getSupportedVersions()
    {
        return array("5.1.0", "7.0");
    }
    
    /**
     * Get xml namespace
     *
     * @param string $a_entity
     * @param string $a_schema_version
     * @return string
     */
    protected function getXmlNamespace($a_entity, $a_schema_version)
    {
        return "http://www.ilias.de/xml/Services/Skill/" . $a_entity;
    }
    
    /**
     * Get field types for entity
     *
     * @param string $a_entity
     * @param string $a_version
     * @return array
     */
    protected function getTypes($a_entity, $a_version)
    {
        if ($a_entity == "skmg") {
            switch ($a_version) {
                case "5.1.0":
                case "7.0":
                    return array(
                            "Mode" => "text"
                    );
            }
        }
        if ($a_entity == "skl_subtree") {
            switch ($a_version) {
                case "5.1.0":
                case "7.0":
                    return array(
                            "SklTreeId" => "integer",
                            "TopNode" => "integer",
                            "Child" => "integer",
                            "Parent" => "integer",
                            "Depth" => "integer",
                            "Type" => "text",
                            "Title" => "text",
                            "Description" => "text",
                            "SelfEval" => "integer",
                            "OrderNr" => "integer",
                            "Status" => "integer",
                            "TemplateId" => "integer"
                    );
            }
        }
        if ($a_entity == "skl_templ_subtree") {
            switch ($a_version) {
                case "5.1.0":
                case "7.0":
                    return array(
                            "SklTreeId" => "integer",
                            "TopNode" => "integer",
                            "Child" => "integer",
                            "Parent" => "integer",
                            "Depth" => "integer",
                            "Type" => "text",
                            "Title" => "text",
                            "Description" => "text",
                            "SelfEval" => "integer",
                            "OrderNr" => "integer",
                            "Status" => "integer"
                    );
            }
        }
        if ($a_entity == "skl_level") {
            switch ($a_version) {
                case "5.1.0":
                case "7.0":
                    return array(
                            "LevelId" => "integer",
                            "SkillId" => "integer",
                            "Nr" => "integer",
                            "Title" => "text",
                            "Description" => "text"
                    );
            }
        }
        if ($a_entity == "skl_prof") {
            switch ($a_version) {
                case "5.1.0":
                case "7.0":
                    return array(
                        "Id" => "integer",
                        "Title" => "text",
                        "Description" => "text"
                    );
            }
        }
        if ($a_entity == "skl_local_prof") {
            switch ($a_version) {
                case "7.0":
                    return array(
                            "Id" => "integer",
                            "Title" => "text",
                            "Description" => "text",
                            "RefId" => "integer"
                    );
            }
        }
        if ($a_entity == "skl_prof_level") {
            switch ($a_version) {
                case "5.1.0":
                    return array(
                            "ProfileId" => "integer",
                            "BaseSkillId" => "integer",
                            "TrefId" => "integer",
                            "LevelId" => "integer"
                    );
                case "7.0":
                    return array(
                        "ProfileId" => "integer",
                        "BaseSkillId" => "integer",
                        "TrefId" => "integer",
                        "LevelId" => "integer",
                        "OrderNr" => "integer"
                    );
            }
        }
        return array();
    }

    /**
     * Read data
     *
     * @param string $a_entity
     * @param string $a_version
     * @param array $a_ids
     * @param string $a_field
     */
    public function readData($a_entity, $a_version, $a_ids, $a_field = "")
    {
        $ilDB = $this->db;

        $this->data = array();

        if (!is_array($a_ids)) {
            $a_ids = array($a_ids);
        }
        if ($a_entity == "skmg") {	// dummy node
            switch ($a_version) {
                case "5.1.0":
                case "7.0":
                    if ($this->getMode() == self::MODE_SKILLS) {
                        $this->data[] = array("Mode" => "Skills");
                    } elseif ($this->getMode() == self::MODE_PROFILES) {
                        $this->data[] = array("Mode" => "Profiles");
                    }
                    break;

            }
        }
        if ($a_entity == "skl_subtree") {	// get subtree for top node
            switch ($a_version) {
                case "5.1.0":
                case "7.0":
                    foreach ($a_ids as $id) {
                        $sub = $this->skill_tree->getSubTree($this->skill_tree->getNodeData($id));
                        foreach ($sub as $s) {
                            $set = $ilDB->query(
                                "SELECT * FROM skl_templ_ref " .
                                " WHERE skl_node_id = " . $ilDB->quote($s["child"], "integer")
                                );
                            $rec = $ilDB->fetchAssoc($set);

                            $top_node = ($s["child"] == $id)
                                    ? 1
                                    : 0;
                            $this->data[] = array(
                                    "SklTreeId" => $s["skl_tree_id"],
                                    "TopNode" => $top_node,
                                    "Child" => $s["child"],
                                    "Parent" => $s["parent"],
                                    "Depth" => $s["depth"],
                                    "Type" => $s["type"],
                                    "Title" => $s["title"],
                                    "Description" => $s["description"],
                                    "SelfEval" => $s["self_eval"],
                                    "OrderNr" => $s["order_nr"],
                                    "Status" => $s["status"],
                                    "TemplateId" => (int) $rec["templ_id"]
                                );
                        }
                    }
                    break;

            }
        }

        if ($a_entity == "skl_templ_subtree") {	// get template subtree for template id
            switch ($a_version) {
                case "5.1.0":
                case "7.0":
                    foreach ($a_ids as $id) {
                        $sub = $this->skill_tree->getSubTree($this->skill_tree->getNodeData($id));
                        foreach ($sub as $s) {
                            $top_node = ($s["child"] == $id)
                                    ? 1
                                    : 0;
                            $this->data[] = array(
                                    "SklTreeId" => $s["skl_tree_id"],
                                    "TopNode" => $top_node,
                                    "Child" => $s["child"],
                                    "Parent" => $s["parent"],
                                    "Depth" => $s["depth"],
                                    "Type" => $s["type"],
                                    "Title" => $s["title"],
                                    "Description" => $s["description"],
                                    "SelfEval" => $s["self_eval"],
                                    "OrderNr" => $s["order_nr"],
                                    "Status" => $s["status"]
                            );
                        }
                    }
                    break;

            }
        }

        if ($a_entity == "skl_level") {
            switch ($a_version) {
                case "5.1.0":
                case "7.0":
                    $this->getDirectDataFromQuery("SELECT id level_id, skill_id, nr, title, description" .
                            " FROM skl_level WHERE " .
                            $ilDB->in("skill_id", $a_ids, false, "integer") . " ORDER BY skill_id ASC, nr ASC");
                    break;

            }
        }

        if ($a_entity == "skl_prof") {
            switch ($a_version) {
                case "5.1.0":
                case "7.0":
                    $this->getDirectDataFromQuery("SELECT id, title, description" .
                            " FROM skl_profile WHERE " .
                            $ilDB->in("id", $a_ids, false, "integer"));
                    break;

            }
        }

        if ($a_entity == "skl_local_prof") {
            switch ($a_version) {
                case "7.0":
                    foreach ($a_ids as $obj_id) {
                        $obj_ref_id = ilObject::_getAllReferences($obj_id);
                        $obj_ref_id = end($obj_ref_id);
                        $profiles = ilSkillProfile::getLocalProfiles($obj_ref_id);
                        $profile_ids = array();
                        foreach ($profiles as $p) {
                            $profile_ids[] = $p["id"];
                        }
                        $set = $ilDB->query(
                            "SELECT * FROM skl_profile " .
                            " WHERE ". $ilDB->in("id", $profile_ids, false, "integer")
                        );
                        while ($rec = $ilDB->fetchAssoc($set)) {
                            $this->data[] = [
                                "Id" => $rec["id"],
                                "Title" => $rec["title"],
                                "Description" => $rec["description"],
                                "RefId" => $obj_ref_id
                            ];
                        }
                    }
                    break;

            }
        }

        if ($a_entity == "skl_prof_level") {
            switch ($a_version) {
                case "5.1.0":
                    $this->getDirectDataFromQuery("SELECT profile_id, base_skill_id, tref_id, level_id" .
                            " FROM skl_profile_level WHERE " .
                            $ilDB->in("profile_id", $a_ids, false, "integer"));
                    break;
                case "7.0":
                    $this->getDirectDataFromQuery("SELECT profile_id, base_skill_id, tref_id, level_id, order_nr" .
                        " FROM skl_profile_level WHERE " .
                        $ilDB->in("profile_id", $a_ids, false, "integer"));
                    break;
            }
        }
    }
    
    /**
     * Determine the dependent sets of data
     */
    protected function getDependencies($a_entity, $a_version, $a_rec, $a_ids)
    {
        $ilDB = $this->db;

        switch ($a_entity) {
            case "skmg":

                $deps = array();
                if ($this->getMode() == self::MODE_SKILLS) {
                    // determine top nodes of main tree to be exported and all referenced template nodes
                    $sel_nodes = $this->getSelectedNodes();
                    $exp_types = array("skll", "scat", "sctr", "sktr");
                    if (!is_array($sel_nodes)) {
                        $childs = $this->skill_tree->getChildsByTypeFilter($this->skill_tree->readRootId(), $exp_types);
                        $skl_subtree_deps = array();
                        foreach ($childs as $c) {
                            $skl_subtree_deps[] = $c["child"];
                        }
                    } else {
                        $skl_subtree_deps = array();
                        foreach ($sel_nodes as $n) {
                            if (in_array(ilSkillTreeNode::_lookupType((int) $n), $exp_types)) {
                                $skl_subtree_deps[] = $n;
                            }
                        }
                    }

                    // determine template subtrees
                    $ref_nodes = array();
                    if (is_array($skl_subtree_deps)) {
                        foreach ($skl_subtree_deps as $id) {
                            if (ilSkillTreeNode::_lookupType($id) == "sktr") {
                                $ref_nodes[$id] = $id;
                            } else {
                                $sub = $this->skill_tree->getSubTree($this->skill_tree->getNodeData($id), true, "sktr");
                                foreach ($sub as $s) {
                                    $ref_nodes[$s["child"]] = $s["child"];
                                }
                            }
                        }
                    }

                    $set = $ilDB->query("SELECT DISTINCT(templ_id) FROM skl_templ_ref " .
                            " WHERE " . $ilDB->in("skl_node_id", $ref_nodes, false, "integer"));
                    while ($rec = $ilDB->fetchAssoc($set)) {
                        $deps["skl_templ_subtree"]["ids"][] = $rec["templ_id"];
                    }

                    // export subtree after templates
                    $deps["skl_subtree"]["ids"] = $skl_subtree_deps;
                } elseif ($this->getMode() == self::MODE_PROFILES) {
                    foreach ($this->getSelectedProfiles() as $p_id) {
                        $deps["skl_prof"]["ids"][] = $p_id;
                    }
                }

                return $deps;

            case "skl_subtree":
            case "skl_templ_subtree":
                $deps = array();
                if (in_array($a_rec["Type"], array("skll", "sktp"))) {
                    $deps["skl_level"]["ids"][] = $a_rec["Child"];
                }
                return $deps;

            case "skl_prof":
            case "skl_local_prof":
                $deps["skl_prof_level"]["ids"][] = $a_rec["Id"];
                return $deps;
        }

        return false;
    }


    /**
     * Import record
     *
     * @param $a_entity
     * @param $a_types
     * @param $a_rec
     * @param $a_mapping
     * @param $a_schema_version
     */
    public function importRecord($a_entity, $a_types, $a_rec, $a_mapping, $a_schema_version)
    {
        $source_inst_id = $a_mapping->getInstallId();
        switch ($a_entity) {
            case "skl_subtree":
                if ($a_rec["TopNode"] == 1) {
                    $parent = $this->skill_tree_root_id;
                    $status = ilSkillTreeNode::STATUS_DRAFT;
                    $order = $a_rec["OrderNr"] + $this->init_top_order_nr;
                } else {
                    $parent = (int) $a_mapping->getMapping("Services/Skill", "skl_tree", $a_rec["Parent"]);
                    $status = $a_rec["Status"];
                    $order = $a_rec["OrderNr"];
                }
                switch ($a_rec["Type"]) {
                    case "scat":
                        $scat = new ilSkillCategory();
                        $scat->setTitle($a_rec["Title"]);
                        $scat->setDescription($a_rec["Description"]);
                        $scat->setImportId("il_" . $source_inst_id . "_scat_" . $a_rec["Child"]);
                        $scat->setSelfEvaluation($a_rec["SelfEval"]);
                        $scat->setOrderNr($order);
                        $scat->setStatus($status);
                        $scat->create();
                        ilSkillTreeNode::putInTree($scat, $parent);
                        $a_mapping->addMapping("Services/Skill", "skl_tree", $a_rec["Child"], $scat->getId());
                        break;

                    case "skll":
                        $skll = new ilBasicSkill();
                        $skll->setTitle($a_rec["Title"]);
                        $skll->setDescription($a_rec["Description"]);
                        $skll->setImportId("il_" . $source_inst_id . "_skll_" . $a_rec["Child"]);
                        $skll->setSelfEvaluation($a_rec["SelfEval"]);
                        $skll->setOrderNr($order);
                        $skll->setStatus($status);
                        $skll->create();
                        ilSkillTreeNode::putInTree($skll, $parent);
                        $a_mapping->addMapping("Services/Skill", "skl_tree", $a_rec["Child"], $skll->getId());
                        break;

                    case "sktr":
                        $template_id = (int) $a_mapping->getMapping("Services/Skill", "skl_tree", $a_rec["TemplateId"]);
                        // only create template references, if referenced template is found (template trees are imported first)
                        if ($template_id > 0) {
                            $sktr = new ilSkillTemplateReference();
                            $sktr->setTitle($a_rec["Title"]);
                            $sktr->setDescription($a_rec["Description"]);
                            $sktr->setImportId("il_" . $source_inst_id . "_sktr_" . $a_rec["Child"]);
                            $sktr->setSelfEvaluation($a_rec["SelfEval"]);
                            $sktr->setOrderNr($order);
                            $sktr->setSkillTemplateId($template_id);
                            $sktr->setStatus($status);
                            $sktr->create();
                            ilSkillTreeNode::putInTree($sktr, $parent);
                            $a_mapping->addMapping("Services/Skill", "skl_tree", $a_rec["Child"], $sktr->getId());
                        }
                        break;

                }
                break;

            case "skl_templ_subtree":
                if ($a_rec["TopNode"] == 1) {
                    $parent = $this->skill_tree_root_id;
                    $order = $a_rec["OrderNr"] + $this->init_templ_top_order_nr;
                } else {
                    $parent = (int) $a_mapping->getMapping("Services/Skill", "skl_tree", $a_rec["Parent"]);
                    $order = $a_rec["OrderNr"];
                }
                switch ($a_rec["Type"]) {
                    case "sctp":
                        $sctp = new ilSkillTemplateCategory();
                        $sctp->setTitle($a_rec["Title"]);
                        $sctp->setDescription($a_rec["Description"]);
                        $sctp->setImportId("il_" . $source_inst_id . "_sctp_" . $a_rec["Child"]);
                        $sctp->setOrderNr($order);
                        $sctp->create();
                        ilSkillTreeNode::putInTree($sctp, $parent);
                        $a_mapping->addMapping("Services/Skill", "skl_tree", $a_rec["Child"], $sctp->getId());
                        break;

                    case "sktp":
                        $sktp = new ilBasicSkillTemplate();
                        $sktp->setTitle($a_rec["Title"]);
                        $sktp->setDescription($a_rec["Description"]);
                        $sktp->setImportId("il_" . $source_inst_id . "_sktp_" . $a_rec["Child"]);
                        $sktp->setOrderNr($order);
                        $sktp->create();
                        ilSkillTreeNode::putInTree($sktp, $parent);
                        $a_mapping->addMapping("Services/Skill", "skl_tree", $a_rec["Child"], $sktp->getId());
                        break;
                }
                break;

            case "skl_level":
                $skill_id = (int) $a_mapping->getMapping("Services/Skill", "skl_tree", $a_rec["SkillId"]);
                $type = ilSkillTreeNode::_lookupType($skill_id);
                if (in_array($type, array("skll", "sktp"))) {
                    if ($type == "skll") {
                        $skill = new ilBasicSkill($skill_id);
                    } else {
                        $skill = new ilBasicSkillTemplate($skill_id);
                    }
                    $skill->addLevel($a_rec["Title"], $a_rec["Description"], "il_" . $source_inst_id . "_sklv_" . $a_rec["LevelId"]);
                    $skill->update();
                }
                break;

            case "skl_prof":
                $prof = new ilSkillProfile();
                $prof->setTitle($a_rec["Title"]);
                $prof->setDescription($a_rec["Description"]);
                $prof->create();
                $a_mapping->addMapping("Services/Skill", "skl_prof", $a_rec["Id"], $prof->getId());
                break;

            case "skl_local_prof":
                $prof = new ilSkillProfile();
                $prof->setTitle($a_rec["Title"]);
                $prof->setDescription($a_rec["Description"]);
                $prof->setRefId($a_rec["RefId"]);
                $prof->create();
                $a_mapping->addMapping("Services/Skill", "skl_local_prof", $a_rec["Id"], $prof->getId());
                break;

            case "skl_prof_level":
                $profile_id = (int) $a_mapping->getMapping("Services/Skill", "skl_prof", $a_rec["ProfileId"])
                    ? (int) $a_mapping->getMapping("Services/Skill", "skl_prof", $a_rec["ProfileId"])
                    : (int) $a_mapping->getMapping("Services/Skill", "skl_local_prof", $a_rec["ProfileId"]);
                if ($profile_id > 0) {
                    $prof = new ilSkillProfile($profile_id);
                    $level_id_data = ilBasicSkill::getLevelIdForImportId($this->getCurrentInstallationId(), $a_rec["LevelId"]);
                    $skill_data = ilBasicSkill::getCommonSkillIdForImportId($this->getCurrentInstallationId(), $a_rec["BaseSkillId"], $a_rec["TrefId"]);
                    //var_dump($level_id_data);
                    //var_dump($skill_data);
                    $level_id = $tref_id = $base_skill = 0;
                    foreach ($level_id_data as $l) {
                        reset($skill_data);
                        foreach ($skill_data as $s) {
                            //		echo "<br>=".ilBasicSkill::lookupLevelSkillId($l["level_id"])."=".$s["skill_id"]."=";

                            if ($level_id == 0 && ilBasicSkill::lookupLevelSkillId($l["level_id"]) == $s["skill_id"]) {
                                $level_id = $l["level_id"];
                                $base_skill = $s["skill_id"];
                                $tref_id = $s["tref_id"];
                            }
                        }
                    }
                    if ($level_id > 0) {
                        $prof->addSkillLevel($base_skill, $tref_id, $level_id, $a_rec["OrderNr"]);
                    }
                    $prof->update();
                }
                break;
        }
    }
}
