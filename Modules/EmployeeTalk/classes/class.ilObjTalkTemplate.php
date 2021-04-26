<?php
declare(strict_types=1);

final class ilObjTalkTemplate extends ilContainer
{
    const TYPE = 'talt';

    /**
     * @var int
     */
    protected static $root_ref_id;
    /**
     * @var int
     */
    protected static $root_id;


    /**
     * @param int  $a_id
     * @param bool $a_call_by_reference
     */
    public function __construct($a_id = 0, $a_call_by_reference = true)
    {
        $this->setType(self::TYPE);
        parent::__construct($a_id, $a_call_by_reference);
    }


    public function read()
    {
        parent::read();
    }


    public function create()
    {
        $this->setOfflineStatus(true);
        parent::create();
        $this->_writeContainerSetting($this->getId(), ilObjectServiceSettingsGUI::CUSTOM_METADATA, true);
        //$this->_writeContainerSetting($this->getId(), ilObjectServiceSettingsGUI::ORGU_POSITION_ACCESS, true);
    }


    public function update()
    {
        parent::update();
    }


    /**
     * @return int
     */
    public static function getRootOrgRefId() : int
    {
        self::loadRootOrgRefIdAndId();

        return self::$root_ref_id;
    }


    /**
     * @return int
     */
    public static function getRootOrgId() : int
    {
        self::loadRootOrgRefIdAndId();

        return self::$root_id;
    }


    private static function loadRootOrgRefIdAndId() : void
    {
        if (self::$root_ref_id === null || self::$root_id === null) {
            global $DIC;
            $ilDB = $DIC['ilDB'];
            $q = "SELECT o.obj_id, r.ref_id FROM object_data o
			INNER JOIN object_reference r ON r.obj_id = o.obj_id
			WHERE title = " . $ilDB->quote('__TalkTemplateAdministration', 'text') . "";
            $set = $ilDB->query($q);
            $res = $ilDB->fetchAssoc($set);
            self::$root_id = (int) $res["obj_id"];
            self::$root_ref_id = (int) $res["ref_id"];
        }
    }


    /**
     * @param        $a_id
     * @param bool   $a_reference
     * @param string $type
     *
     * @return bool
     */
    public static function _exists($a_id, $a_reference = false, $type = null)
    {
        return parent::_exists($a_id, $a_reference, self::TYPE);
    }

    /**
     * delete orgunit, childs and all related data
     *
     * @return    boolean    true if all object data were removed; false if only a references were
     *                       removed
     */
    public function delete(): bool
    {
        return parent::delete();
    }
}