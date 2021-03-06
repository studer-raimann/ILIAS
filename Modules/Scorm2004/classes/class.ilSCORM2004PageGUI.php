<?php

/* Copyright (c) 1998-2021 ILIAS open source, GPLv3, see LICENSE */

/**
 * Class ilSCORM2004Page GUI class
 *
 * @author Alex Killing <alex.killing@gmx.de>
 *
 * @ilCtrl_Calls ilSCORM2004PageGUI: ilPageEditorGUI, ilEditClipboardGUI, ilMediaPoolTargetSelector
 * @ilCtrl_Calls ilSCORM2004PageGUI: ilRatingGUI, ilPublicUserProfileGUI, ilPageObjectGUI, ilNoteGUI
 * @ilCtrl_Calls ilSCORM2004PageGUI: ilObjectMetaDataGUI, ilQuestionEditGUI, ilAssQuestionFeedbackEditingGUI
 */
class ilSCORM2004PageGUI extends ilPageObjectGUI
{
    protected $glossary_links = array();
    protected $scorm_mode = "preview";
    public static $export_glo_tpl;
    
    /**
    * Constructor
    */
    public function __construct(
        $a_parent_type,
        $a_id = 0,
        $a_old_nr = 0,
        $a_slm_id = 0,
        $a_glo_id = 0
    ) {
        global $DIC;

        $this->tpl = $DIC["tpl"];
        $this->ctrl = $DIC->ctrl();
        $ilCtrl = $DIC->ctrl();

        $this->glo_id = $a_glo_id;
        $this->slm_id = $a_slm_id;

        $a_parent_type = "sahs";

        parent::__construct($a_parent_type, $a_id, $a_old_nr);
        $this->getPageObject()->setGlossaryId($this->glo_id);

        $this->enableNotes(true, $this->slm_id);
    }
    
    /**
     * Set glossary overview id
     *
     * @param	string	glossary overview id
     */
    public function setGlossaryOverviewInfo($a_ov_id, $a_sco)
    {
        $this->glossary_ov_id = $a_ov_id;
        $this->sco = $a_sco;
    }
    
    /**
     * Get glossary overview id
     *
     * @return	string	glossary overview id
     */
    public function getGlossaryOverviewId()
    {
        return $this->glossary_ov_id;
    }
    
    /**
    * execute command
    */
    public function executeCommand()
    {
        $ilCtrl = $this->ctrl;

        $this->setIntLinkReturn(
            $ilCtrl->getLinkTargetByClass(
                "ilobjscorm2004learningmodulegui",
                "showTree",
                "",
                false,
                false
            )
        );

        $next_class = $this->ctrl->getNextClass($this);
        $cmd = $this->ctrl->getCmd();

        switch ($next_class) {
            case 'ilmdeditorgui':
                return parent::executeCommand();
                break;

            case "ilpageobjectgui":
die("ilSCORM2004PageGUI forwarding to ilpageobjectgui error.");
                return;
                
            default:
                $html = parent::executeCommand();
                return $html;
        }
    }

    /**
    * Set SCORM2004 Page Object.
    *
    * @param	object	$a_scpage	Page Object
    */
    public function setSCORM2004Page($a_scpage)
    {
        $this->setPageObject($a_scpage);
    }

    /**
    * Get SCORM2004 Page Object.
    *
    * @return	object		Page Object
    */
    public function getSCORM2004Page()
    {
        return $this->getPageObject();
    }

    /*function preview()
    {
        global $DIC;
        $ilCtrl = $DIC['ilCtrl'];

        $wtpl = new ilTemplate("tpl....html",
            true, true, "Modules/Scorm2004");

        $wtpl->setVariable("PAGE", parent::preview());
        return $wtpl->get();
    }*/
    
    /**
    * Get question html for page
    */
    public function getQuestionHtmlOfPage()
    {
        $q_ids = $this->getPageObject()->getQuestionIds();

        $html = array();
        if (count($q_ids) > 0) {
            foreach ($q_ids as $q_id) {
                $q_gui = assQuestionGUI::_getQuestionGUI("", $q_id);
                $q_gui->setRenderPurpose(assQuestionGUI::RENDER_PURPOSE_PREVIEW);
                $q_gui->outAdditionalOutput();
                $html[$q_id] = $q_gui->getPreview(true);
            }
        }

        return $html;
    }
    

    /**
     * Init question handling
     *
     * @param
     * @return
     */
    public function initSelfAssessmentRendering()
    {
        if ($this->scorm_mode == "preview") {
            // parent::initSelfAssessmentRendering();		// todo: not called anymore
        }
    }
    
    /**
     * Self assessment question rendering
     *
     * @param
     * @return
     */
    public function selfAssessmentRendering($a_output)
    {
        if ($this->scorm_mode == "preview") {
            $a_output = parent::selfAssessmentRendering($a_output);
        }

        return $a_output;
    }
    
    /**
    * Show the page
    */
    public function showPage($a_mode = "preview")
    {
        $tpl = $this->tpl;
        $ilCtrl = $this->ctrl;
        
        $this->scorm_mode = $a_mode;
                        
        $this->setTemplateOutput(false);
        
        $output = parent::showPage();
        
        return $output;
    }
    
    /**
     * Set standard link xml (currently only glossaries)
     */
    public function setDefaultLinkXml()
    {
        $int_links = $this->getPageObject()->getInternalLinks(true);
        $this->glossary_links = $int_links;
        //var_dump($int_links);

        // key is il__git_18:GlossaryItem:Glossary::4 => id is il__git_18_4,

        $link_info = "<IntLinkInfos>";
        $targetframe = "None";
        $ltarget = "";
        foreach ($int_links as $int_link) {
            $onclick = "";
            $target = $int_link["Target"];
            $targetframe = "None";
            if (substr($target, 0, 4) == "il__") {
                $target_arr = explode("_", $target);
                $target_id = $target_arr[count($target_arr) - 1];
                $type = $int_link["Type"];
                
                switch ($type) {
                    case "GlossaryItem":
                        $ltarget = "";
                        //$href = "./goto.php?target=git_".$target_id;
                        $href = "#";
                        $onclick = 'OnClick="return false;"';
                        $anc_par = 'Anchor=""';
                        $targetframe = "Glossary";
                        break;
                    
                    case "File":
                        $ltarget = "";
                        if ($this->getOutputMode() == "offline") {
                            if (ilObject::_lookupType($target_id) == "file") {
                                $href = "./files/file_" . $target_id . "/" . ilObjFile::_lookupFileName($target_id);
                                $ltarget = "_blank";
                            }
                        } else {
                            $href = str_replace("&", "&amp;", $this->determineFileDownloadLink()) . "&amp;file_id=il__file_" . $target_id;
                            //echo htmlentities($href);
                        }
                        
                        $anc_par = 'Anchor=""';
                        $targetframe = "None"; //???
                        break;

                }
                $link_info .= "<IntLinkInfo $onclick Target=\"$target\" Type=\"$type\" " . $anc_par . " " .
                    "TargetFrame=\"$targetframe\" LinkHref=\"$href\" LinkTarget=\"$ltarget\" />";
            }
        }
        $link_info .= "</IntLinkInfos>";
        $this->setLinkXML($link_info);
        //var_dump($link_info);
    }
    
    /**
     * Post output processing:
     * - Add glossary divs
     */
    public function postOutputProcessing($a_output)
    {
        if ($this->scorm_mode != "export") {
            $tpl = new ilTemplate("tpl.glossary_entries.html", true, true, "Modules/Scorm2004");
        } else {
            $tpl = self::$export_glo_tpl;
        }
        $glossary = false;

        $overlays = array();

        // overlay for sco glossary
        if ($this->getGlossaryOverviewId() != "" && $this->getOutputMode() != "edit") {
            $ovov = $overlays[$this->getGlossaryOverviewId()] = new ilOverlayGUI($this->getGlossaryOverviewId());
            //			$ovov->setFixedCenter(true);
            $ovov->setAutoHide(false);
            $ovov->setCloseElementId("glo_ov_close");
            if ($this->getGlossaryOverviewId() != "") {
                if ($this->scorm_mode != "export" ||
                    $this->getOutputMode() == ilPageObjectGUI::PREVIEW) {
                    $overlays[$this->getGlossaryOverviewId()]->add();
                } else {
                    $tpl->setCurrentBlock("add_script");
                    $tpl->setVariable("ADD_SCRIPT", "il.Util.addOnLoad(function () {" . $overlays[$this->getGlossaryOverviewId()]->getOnLoadCode() . "});");
                    $tpl->parseCurrentBlock();
                }
            }
        }

        if ($this->getOutputMode() != "edit") {
            if (is_array($this->glossary_links)) {
                foreach ($this->glossary_links as $k => $e) {
                    // glossary link
                    if ($e["Type"] == "GlossaryItem") {
                        $karr = explode(":", $k);
                        $link_id = $karr[0] . "_" . $this->getPageObject()->getId() . "_" . $karr[4];
                        //$ov_id = "ov".$karr[0]."_".$karr[4];
                        $ov_id = "ov" . $karr[0];
                        $cl_id = "ov" . $karr[0] . "cl";
                        $glov_id = "ov" . $karr[0] . "ov";
                        $term_id_arr = explode("_", $karr[0]);
                        $term_id = $term_id_arr[count($term_id_arr) - 1];
    
                        // get overlay html from glossary term
                        $id_arr = explode("_", $karr[0]);
                        $term_gui = new ilGlossaryTermGUI($id_arr[count($id_arr) - 1]);
                        $html = $term_gui->getOverlayHTML(
                            $cl_id,
                            ($this->getGlossaryOverviewId() != "")
                                                          ? $glov_id
                                                          : "",
                            ilObjSAHSLearningModule::getAffectiveLocalization($this->slm_id)
                        );
                        $tpl->setCurrentBlock("entry");
                        $tpl->setVariable("CONTENT", $html);
                        $tpl->setVariable("OVERLAY_ID", $ov_id);
        
                        $glossary = true;
    
                        // first time the term is used
                        if (!isset($overlays[$ov_id])) {
                            $overlays[$ov_id] = new ilOverlayGUI($ov_id);
                            $overlays[$ov_id]->setAnchor($link_id);
                            $overlays[$ov_id]->setTrigger($link_id, "click", $link_id);
                            $overlays[$ov_id]->setAutoHide(false);
                            $overlays[$ov_id]->setCloseElementId($cl_id);
                            if ($this->scorm_mode != "export" ||
                                $this->getOutputMode() == ilPageObjectGUI::PREVIEW) {
                                $overlays[$ov_id]->add();
                            } else {
                                $tpl->setVariable("SCRIPT", "il.Util.addOnLoad(function () {" . $overlays[$ov_id]->getOnLoadCode() . "});");
                            }
                        } else {
                            if ($this->scorm_mode != "export" ||
                                $this->getOutputMode() == ilPageObjectGUI::PREVIEW) {
                                $overlays[$ov_id]->addTrigger($link_id, "click", $link_id);
                            } else {
                                $tpl->setVariable(
                                    "SCRIPT",
                                    "il.Util.addOnLoad(function () {" . $overlays[$ov_id]->getTriggerOnLoadCode($link_id, "click", $link_id) . "});"
                                );
                            }
                        }
                        
                        if ($this->getGlossaryOverviewId() != "") {
                            if ($this->scorm_mode != "export" ||
                                $this->getOutputMode() == ilPageObjectGUI::PREVIEW) {
                                //$overlays[$this->getGlossaryOverviewId()]->addTrigger($glov_id, "click", null);
                                $overlays[$this->getGlossaryOverviewId()]->addTrigger($glov_id, "click", $ov_id, false, "tl", "tl");
                                //$overlays[$ov_id]->addTrigger("glo_ov_t".$term_id, "click", null, true);
                                $overlays[$ov_id]->addTrigger("glo_ov_t" . $term_id, "click", $this->getGlossaryOverviewId(), false, "tl", "tl");
                            } else {
                                $tpl->setVariable(
                                    "SCRIPT2",
                                    "il.Util.addOnLoad(function () {" .
                                    $overlays[$this->getGlossaryOverviewId()]->getTriggerOnLoadCode($glov_id, "click", $ov_id, false, "tl", "tl") . "});"
                                );
                                $tpl->setVariable(
                                    "SCRIPT3",
                                    "il.Util.addOnLoad(function () {" .
                                    $overlays[$ov_id]->getTriggerOnLoadCode("glo_ov_t" . $term_id, "click", $this->getGlossaryOverviewId(), false, "tl", "tl") . "});"
                                );
                            }
                        }
                        
                        $tpl->parseCurrentBlock();
                    }
                }
            }
            
            if ($glossary && $this->scorm_mode != "export") {
                $ret = $a_output . $tpl->get();
                if ($this->getGlossaryOverviewId() != "") {
                    $ret .= ilSCORM2004ScoGUI::getGloOverviewOv($this->sco);
                }
                return $ret;
            }
        }

        return $a_output;
    }
    
    /**
     * Init export
     */
    public static function initExport()
    {
        self::$export_glo_tpl = new ilTemplate("tpl.glossary_entries.html", true, true, "Modules/Scorm2004");
    }
    
    /**
     * Get glossary html (only in export mode)
     */
    public static function getGlossaryHTML($a_sco)
    {
        $ret = self::$export_glo_tpl->get();
        
        $ret .= ilSCORM2004ScoGUI::getGloOverviewOv($a_sco);
        
        return $ret;
    }
}
