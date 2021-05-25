<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Settings for a single didactic template
 *
 * @author Stefan Meyer <meyer@leifos.com>
 * @defgroup ServicesDidacticTemplate
 */
class ilDidacticTemplateSetting
{
    const TYPE_CREATION = 1;


    private $id = 0;
    private $enabled = false;
    private $title = '';
    private $description = '';
    private $info = '';
    private $type = self::TYPE_CREATION;
    private $assignments = array();
    private $effective_from = array();
    private $auto_generated = false;
    private $exclusive = false;

    private $icon_ide = '';

    private $iconHandler = null;


    /**
     * Constructor
     * @param int $a_id
     */
    public function __construct($a_id = 0)
    {
        $this->setId($a_id);
        $this->read();
        $this->iconHandler = new ilDidacticTemplateIconHandler($this);
    }

    /**
     * @return ilDidacticTemplateIconHandler
     */
    public function getIconHandler() : ilDidacticTemplateIconHandler
    {
        return $this->iconHandler;
    }

    /**
     * Set id
     * @param int $a_id
     */
    protected function setId($a_id)
    {
        $this->id = $a_id;
    }

    /**
     * Get id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set enabled status
     * @param bool $a_status
     */
    public function enable($a_status)
    {
        $this->enabled = $a_status;
    }

    /**
     * Check if template is enabled
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set title
     * @param string $a_title
     */
    public function setTitle($a_title)
    {
        $this->title = $a_title;
    }

    /**
     * Get title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $a_lng
     * @return string
     */
    public function getPresentationTitle($a_lng = "")
    {
        if ($this->isAutoGenerated()) {
            global $DIC;

            $lng = $DIC['lng'];
            return $lng->txt($this->getTitle());
        }

        $tit = $this->getPresentation('title', $a_lng);
        return $tit ? $tit : $this->getTitle();
    }

    /**
     * Get description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $a_lng
     * @return string
     */
    public function getPresentationDescription($a_lng = "")
    {
        if ($this->isAutoGenerated()) {
            global $DIC;

            $lng = $DIC['lng'];
            return $lng->txt($this->getDescription());
        }

        $desc = $this->getPresentation('description', $a_lng);
        return $desc ? $desc : $this->getDescription();
    }

    /**
     * Set description
     * @param string $a_description
     */
    public function setDescription($a_description)
    {
        $this->description = $a_description;
    }

    /**
     * Set installation info text
     * @param string $a_info
     */
    public function setInfo($a_info)
    {
        $this->info = $a_info;
    }

    /**
     * Get installation info text
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set type
     * @param int $a_type
     */
    public function setType($a_type)
    {
        $this->type = $a_type;
    }

    /**
     * Get type
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param ilObjectDefinition $definition
     * @return bool
     */
    public function hasIconSupport(ilObjectDefinition $definition) : bool
    {
        foreach ($this->getAssignments() as $assignment) {
            if (!$definition->isContainer($assignment)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Set assignments
     * @param array $a_ass
     */
    public function setAssignments(array $a_ass)
    {
        $this->assignments = (array) $a_ass;
    }

    /**
     * Get object assignemnts
     * @return array
     */
    public function getAssignments()
    {
        return (array) $this->assignments;
    }

    /**
     * Add one assignment obj type
     * @param string $a_obj_type
     */
    public function addAssignment($a_obj_type)
    {
        $this->assignments[] = $a_obj_type;
    }

    /**
     * @return int[]
     */
    public function getEffectiveFrom()
    {
        return $this->effective_from;
    }

    /**
     * @param int[] $effective_from
     */
    public function setEffectiveFrom($effective_from)
    {
        $this->effective_from = $effective_from;
    }

    /**
     * @return bool
     */
    public function isAutoGenerated()
    {
        return $this->auto_generated;
    }

    /**
     * DO NOT CHANGE THIS VALUE
     *
     * @param bool $auto_generated
     */
    private function setAutoGenerated($auto_generated)
    {
        $this->auto_generated = $auto_generated;
    }

    /**
     * @return boolean
     */
    public function isExclusive()
    {
        return $this->exclusive;
    }

    /**
     * @param boolean $exclusive
     */
    public function setExclusive($exclusive)
    {
        $this->exclusive = $exclusive;
    }

    public function setIconIdentifier(string $icon_identifier)
    {
        $this->icon_ide = $icon_identifier;
    }

    public function getIconIdentifier() : string
    {
        return $this->icon_ide;
    }

    /**
     * get all translations from this object
     *
     * @access	public
     * @return	array
     */
    public function getTranslations()
    {
        $trans = $this->getTranslationObject();
        $lang = $trans->getLanguages();

        foreach ($lang as $k => $v) {
            if ($v['lang_default']) {
                $lang[0] = $lang[$k];
            }
        }

        // fallback if translation object is empty
        if (!isset($lang[0])) {
            $lang[0]['title'] = $this->getTitle();
            $lang[0]['description'] = $this->getDescription();
            $lang[0]['lang_code'] = $trans->getDefaultLanguage();
        }

        return $lang;
    }

    protected function getPresentation($a_value, $a_lng)
    {
        $lang = $this->getTranslationObject()->getLanguages();

        if (!$lang) {
            return "";
        }

        if (!$a_lng) {
            global $DIC;

            $ilUser = $DIC['ilUser'];
            $a_lng = $ilUser->getCurrentLanguage();
        }

        if (isset($lang[$a_lng][$a_value])) {
            return $lang[$a_lng][$a_value] ;
        } else {
            return $lang[$a_lng][$this->getTranslationObject()->getDefaultLanguage()];
        }
    }

    /**
     * Delete settings
     */
    public function delete()
    {
        global $DIC;

        $ilDB = $DIC['ilDB'];

        if ($this->isAutoGenerated()) {
            return false;
        }

        // Delete settings
        $query = 'DELETE FROM didactic_tpl_settings ' .
            'WHERE id = ' . $ilDB->quote($this->getId(), 'integer');
        $ilDB->manipulate($query);

        // Delete obj assignments
        $query = 'DELETE FROM didactic_tpl_sa ' .
            'WHERE id = ' . $ilDB->quote($this->getId(), 'integer');
        $ilDB->manipulate($query);

        include_once './Services/DidacticTemplate/classes/class.ilDidacticTemplateActionFactory.php';
        foreach (ilDidacticTemplateActionFactory::getActionsByTemplateId($this->getId()) as $action) {
            $action->delete();
        }

        include_once './Services/DidacticTemplate/classes/class.ilDidacticTemplateObjSettings.php';
        ilDidacticTemplateObjSettings::deleteByTemplateId($this->getId());

        $this->getTranslationObject()->delete();
        $this->deleteEffectiveNodes();
        $this->getIconHandler()->delete();
        return true;
    }

    /**
     * Save settings
     */
    public function save()
    {
        global $DIC;

        $ilDB = $DIC['ilDB'];

        $this->setId($ilDB->nextId('didactic_tpl_settings'));

        $query = 'INSERT INTO didactic_tpl_settings (id,enabled,title,description,info,type,auto_generated,exclusive_tpl,icon_ide) ' .
            'VALUES( ' .
            $ilDB->quote($this->getId(), 'integer') . ', ' .
            $ilDB->quote($this->isEnabled(), 'integer') . ', ' .
            $ilDB->quote($this->getTitle(), 'text') . ', ' .
            $ilDB->quote($this->getDescription(), 'text') . ', ' .
            $ilDB->quote($this->getInfo(), 'text') . ', ' .
            $ilDB->quote($this->getType(), 'integer') . ', ' .
            $ilDB->quote((int) $this->isAutoGenerated(), 'integer') . ', ' .
            $ilDB->quote((int) $this->isExclusive(), 'integer') . ', ' .
            $ilDB->quote((string) $this->getIconIdentifier(), ilDBConstants::T_TEXT) . ' ' .
            ')';

        $ilDB->manipulate($query);

        $this->saveAssignments();

        return true;
    }

    /**
     * Save assignments in DB
     * @return bool
     */
    private function saveAssignments()
    {
        if ($this->isAutoGenerated()) {
            return false;
        }

        foreach ($this->getAssignments() as $ass) {
            $this->saveAssignment($ass);
        }
        return true;
    }

    /**
     * Add one object assignment
     * @param string $a_obj_type
     */
    private function saveAssignment($a_obj_type)
    {
        global $DIC;

        $ilDB = $DIC['ilDB'];

        if ($this->isAutoGenerated()) {
            return;
        }

        $query = 'INSERT INTO didactic_tpl_sa (id,obj_type) ' .
            'VALUES( ' .
            $ilDB->quote($this->getId(), 'integer') . ', ' .
            $ilDB->quote($a_obj_type, 'text') .
            ')';
        $ilDB->manipulate($query);
    }

    /**
     *
     */
    protected function saveEffectiveNodes()
    {
        global $DIC;

        $ilDB = $DIC['ilDB'];
        
        if (!count($this->getEffectiveFrom())) {
            return;
        }
        $values = [];
        foreach ($this->getEffectiveFrom() as $node) {
            $values[] = '( ' .
            $ilDB->quote($this->getId(), 'integer') . ', ' .
            $ilDB->quote($node, 'integer') .
            ')';
        }
        
        $query = 'INSERT INTO didactic_tpl_en (id,node) ' .
            'VALUES ' . implode(', ', $values);
        
        $ilDB->manipulate($query);
    }
    
    protected function deleteEffectiveNodes()
    {
        global $DIC;

        $ilDB = $DIC['ilDB'];

        $query = 'DELETE FROM didactic_tpl_en ' .
            'WHERE id = ' . $ilDB->quote($this->getId(), 'integer');
        $ilDB->manipulate($query);
        return true;
    }
    
    protected function readEffectiveNodes()
    {
        global $DIC;

        $ilDB = $DIC['ilDB'];
        $effective_nodes = array();
        
        $query = 'SELECT * FROM didactic_tpl_en ' .
            'WHERE id = ' . $ilDB->quote($this->getId(), 'integer');
        $res = $ilDB->query($query);
        while ($row = $res->fetchRow(ilDBConstants::FETCHMODE_OBJECT)) {
            $effective_nodes[] = $row->node;
        }
        
        $this->setEffectiveFrom($effective_nodes);
    }

    /**
     * Delete assignments
     * @return bool
     */
    private function deleteAssignments()
    {
        global $DIC;

        $ilDB = $DIC['ilDB'];

        if ($this->isAutoGenerated()) {
            return false;
        }

        $query = 'DELETE FROM didactic_tpl_sa ' .
            'WHERE id = ' . $ilDB->quote($this->getId(), 'integer');
        $ilDB->manipulate($query);
        return true;
    }

    /**
     * Update settings
     * @return bool
     */
    public function update()
    {
        global $DIC;

        $ilDB = $DIC['ilDB'];

        $query = 'UPDATE didactic_tpl_settings ' .
            'SET ' .
            'enabled = ' . $ilDB->quote($this->isEnabled(), 'integer') . ', ' .
            'title = ' . $ilDB->quote($this->getTitle(), 'text') . ', ' .
            'description = ' . $ilDB->quote($this->getDescription(), 'text') . ', ' .
            'info = ' . $ilDB->quote($this->getInfo(), 'text') . ', ' .
            'type = ' . $ilDB->quote($this->getType(), 'integer') . ', ' .
            'exclusive_tpl = ' . $ilDB->quote((int) $this->isExclusive(), 'integer') . ', ' .
            'icon_ide = ' . $ilDB->quote((string) $this->getIconIdentifier(), ilDBConstants::T_TEXT) . ' ' .
            'WHERE id = ' . $ilDB->quote($this->getId(), 'integer');
        $ilDB->manipulate($query);
        $this->deleteAssignments();
        $this->saveAssignments();
        
        $this->deleteEffectiveNodes();
        $this->saveEffectiveNodes();

        return true;
    }

    /**
     * read settings from db
     * @return bool
     */
    protected function read()
    {
        global $DIC;

        $ilDB = $DIC['ilDB'];

        if (!$this->getId()) {
            return false;
        }

        /**
         * Read settings
         */
        $query = 'SELECT * FROM didactic_tpl_settings dtpl ' .
            'WHERE id = ' . $ilDB->quote($this->getId(), 'integer');
        $res = $ilDB->query($query);
        while ($row = $res->fetchRow(ilDBConstants::FETCHMODE_OBJECT)) {
            $this->setType($row->type);
            $this->enable($row->enabled);
            $this->setTitle($row->title);
            $this->setDescription($row->description);
            $this->setInfo($row->info);
            $this->setAutoGenerated((bool) $row->auto_generated);
            $this->setExclusive((bool) $row->exclusive_tpl);
            $this->setIconIdentifier((string) $row->icon_ide);
        }

        /**
         * Read assigned objects
         */
        $query = 'SELECT * FROM didactic_tpl_sa ' .
            'WHERE id = ' . $ilDB->quote($this->getId(), 'integer');
        $res = $ilDB->query($query);
        while ($row = $res->fetchRow(ilDBConstants::FETCHMODE_OBJECT)) {
            $this->addAssignment($row->obj_type);
        }
        
        $this->readEffectiveNodes();

        return true;
    }

    /**
     * Export
     * @param ilXmlWriter $writer
     * @return ilXmlWriter
     */
    public function toXml(ilXmlWriter $writer)
    {
        global $DIC;

        $ilSetting = $DIC['ilSetting'];
        $type = '';
        switch ($this->getType()) {
            case self::TYPE_CREATION:
                $type = 'creation';
                break;
        }
        
        $writer->xmlStartTag('didacticTemplate', array('type' => $type));
        $writer->xmlElement('title', array(), $this->getTitle());
        $writer->xmlElement('description', array(), $this->getDescription());
        $writer = $this->getIconHandler()->toXml($writer);
        $writer = $this->getTranslationObject()->toXml($writer);

        // info text with p-tags
        if (strlen($this->getInfo())) {
            $writer->xmlStartTag('info');

            $info_lines = (array) explode("\n", $this->getInfo());
            foreach ($info_lines as $info) {
                $trimmed_info = trim($info);
                if (strlen($trimmed_info)) {
                    $writer->xmlElement('p', array(), $trimmed_info);
                }
            }

            $writer->xmlEndTag('info');
        }

        if ($this->isExclusive()) {
            $writer->xmlElement("exclusive");
        }


        if (count($this->getEffectiveFrom()) > 0) {
            $writer->xmlStartTag('effectiveFrom', array('nic_id' => $ilSetting->get('inst_id')));

            foreach ($this->getEffectiveFrom() as $node) {
                $writer->xmlElement('node', array(), $node);
            }
            $writer->xmlEndTag('effectiveFrom');
        }

        // Assignments
        $writer->xmlStartTag('assignments');
        foreach ($this->getAssignments() as $assignment) {
            $writer->xmlElement('assignment', array(), $assignment);
        }
        $writer->xmlEndTag('assignments');


        $writer->xmlStartTag('actions');
        include_once './Services/DidacticTemplate/classes/class.ilDidacticTemplateActionFactory.php';
        foreach (ilDidacticTemplateActionFactory::getActionsByTemplateId($this->getId()) as $action) {
            $action->toXml($writer);
        }
        $writer->xmlEndTag('actions');
        $writer->xmlEndTag('didacticTemplate');

        return $writer;
    }

    /**
     * Implemented clone method
     */
    public function __clone()
    {
        $this->setId(0);
        include_once './Services/DidacticTemplate/classes/class.ilDidacticTemplateCopier.php';
        $this->setTitle(ilDidacticTemplateCopier::appendCopyInfo($this->getTitle()));
        $this->enable(false);
        $this->setAutoGenerated(false);
        $this->iconHandler = new ilDidacticTemplateIconHandler($this);
    }

    /**
     * @return ilMultilingualism
     */
    public function getTranslationObject()
    {
        include_once("./Services/Multilingualism/classes/class.ilMultilingualism.php");
        return ilMultilingualism::getInstance($this->getId(), "dtpl");
    }

    /**
     * @param int $a_node_id
     * @return bool
     */
    public function isEffective($a_node_id)
    {
        global $DIC;

        $tree = $DIC['tree'];

        if (!count($this->getEffectiveFrom()) || in_array($a_node_id, $this->getEffectiveFrom())) {
            return true;
        }
        
        foreach ($this->getEffectiveFrom() as $node) {
            if ($tree->isGrandChild($node, $a_node_id)) {
                return true;
            }
        }
        
        return false;
    }
}
