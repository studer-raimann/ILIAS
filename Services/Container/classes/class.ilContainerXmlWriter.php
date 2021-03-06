<?php

/* Copyright (c) 1998-2021 ILIAS open source, GPLv3, see LICENSE */

/**
 * XML writer for container structure
 *
 * @author Stefan Meyer <smeyer.ilias@gmx.de>
 */
class ilContainerXmlWriter extends ilXmlWriter
{
    /**
     * @var ilTree
     */
    protected $tree;

    protected $exp_options = null;
    private $source = 0;

    /**
     * @var ilObjectDefinition
     */
    protected $objDefinition;

    /**
     * Constructor
     */
    public function __construct($a_ref_id)
    {
        global $DIC;

        $this->tree = $DIC->repositoryTree();
        parent::__construct();
        $this->source = $a_ref_id;
        $this->exp_options = ilExportOptions::getInstance();
        $this->objDefinition = $DIC['objDefinition'];
    }
    
    /**
     * Write XML
     * @return
     * @throws UnexpectedValueException Thrown if obj_id is not of type webr or no obj_id is given
     */
    public function write()
    {
        $this->xmlStartTag('Items');
        $this->writeSubitems($this->source);
        $this->xmlEndTag('Items');
    }
    
    /**
     * Write tree childs
     * Recursive method
     * @param object $a_ref_id
     * @return
     */
    protected function writeSubitems($a_ref_id)
    {
        $tree = $this->tree;
    
        // because of the co-page-stuff (incl. styles) we also need to process the container itself
        if ($a_ref_id != $this->source) {
            $mode = $this->exp_options->getOptionByRefId($a_ref_id, ilExportOptions::KEY_ITEM_MODE);
            if ($mode == null or $mode == ilExportOptions::EXPORT_OMIT) {
                return false;
            }
        }

        $obj_id = ilObject::_lookupObjId($a_ref_id);

        $atts = [];
        $atts['RefId'] = $a_ref_id;
        $atts['Id'] = $obj_id;
        $atts['Title'] = ilObject::_lookupTitle($obj_id);
        $atts['Type'] = ilObject::_lookupType($obj_id);
        $atts['Page'] = ilContainerPage::_exists('cont', $obj_id);
        $atts['StartPage'] = ilContainerStartObjectsPage::_exists('cstr', $obj_id);
        $atts['Style'] = ilObjStyleSheet::lookupObjectStyle($obj_id);
        if ($this->objDefinition->supportsOfflineHandling($atts['Type'])) {
            $atts['Offline'] = ilObject::lookupOfflineStatus($obj_id) ? '1' : '0';
        }
        $this->xmlStartTag(
            'Item',
            $atts
        );
        $this->writeCourseItemInformation($a_ref_id);
        
        foreach ($tree->getChilds($a_ref_id) as $node) {
            $this->writeSubitems($node['child']);
        }
        
        $this->xmlEndTag('Item');
        return true;
    }
    
    
    /**
     * Write course item information
     * Starting time, ending time...
     * @param int $a_ref_id
     * @return
     */
    protected function writeCourseItemInformation($a_ref_id)
    {
        $item = ilObjectActivation::getItem($a_ref_id);
        
        $this->xmlStartTag(
            'Timing',
            array(
                'Type' => $item['timing_type'],
                'Visible' => $item['visible'],
                'Changeable' => $item['changeable'],
                )
        );
        if ($item['timing_start']) {
            $tmp_date = new ilDateTime($item['timing_start'], IL_CAL_UNIX);
            $this->xmlElement('Start', array(), $tmp_date->get(IL_CAL_DATETIME, '', ilTimeZone::UTC));
        }
        if ($item['timing_end']) {
            $tmp_date = new ilDateTime($item['timing_end'], IL_CAL_UNIX);
            $this->xmlElement('End', array(), $tmp_date->get(IL_CAL_DATETIME, '', ilTimeZone::UTC));
        }
        if ($item['suggestion_start']) {
            $tmp_date = new ilDateTime($item['suggestion_start'], IL_CAL_UNIX);
            $this->xmlElement('SuggestionStart', array(), $tmp_date->get(IL_CAL_DATETIME, '', ilTimeZone::UTC));
        }
        if ($item['suggestion_end']) {
            $tmp_date = new ilDateTime($item['suggestion_end'], IL_CAL_UNIX);
            $this->xmlElement('SuggestionEnd', array(), $tmp_date->get(IL_CAL_DATETIME, '', ilTimeZone::UTC));
        }
        if ($item['earliest_start']) {
            $tmp_date = new ilDateTime($item['earliest_start'], IL_CAL_UNIX);
            $this->xmlElement('EarliestStart', array(), $tmp_date->get(IL_CAL_DATETIME, '', ilTimeZone::UTC));
        }

        $this->xmlEndTag('Timing');
    }
    
    /**
     * Build XML header
     * @return
     */
    protected function buildHeader()
    {
        $this->xmlSetDtdDef("<!DOCTYPE Container PUBLIC \"-//ILIAS//DTD Container//EN\" \"" . ILIAS_HTTP_PATH . "/xml/ilias_container_4_1.dtd\">");
        $this->xmlSetGenCmt("Container object");
        $this->xmlHeader();

        return true;
    }
}
