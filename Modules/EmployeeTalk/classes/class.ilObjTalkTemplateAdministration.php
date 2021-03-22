<?php
declare(strict_types=1);

final class ilObjTalkTemplateAdministration extends ilContainer
{
    const TABLE_NAME = 'etal_data';
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
        $this->type = "tala";
        parent::__construct($a_id, $a_call_by_reference);
    }


    public function read()
    {
        parent::read();
    }


    public function create()
    {
        parent::create();
    }


    public function update()
    {
        parent::update();
    }


    /**
     * @return int
     */
    public static function getRootRefId() : int
    {
        self::loadRootOrgRefIdAndId();

        return self::$root_ref_id;
    }


    /**
     * @return int
     */
    public static function getRootObjId() : int
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
			WHERE title = " . $ilDB->quote('__TalkTemplateAdministration', 'text');
            $set = $ilDB->query($q);
            $res = $ilDB->fetchAssoc($set);
            self::$root_id = (int) $res["obj_id"];
            self::$root_ref_id = (int) $res["ref_id"];
        }
    }

    public function getTitle(): string
    {
        if (parent::getTitle() !== "__TalkTemplateAdministration") {
            return parent::getTitle();
        } else {
            return $this->lng->txt("objs_tala");
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
        return parent::_exists($a_id, $a_reference, "tala");
    }

    /**
     * delete orgunit, childs and all related data
     *
     * @return    boolean    true if all object data were removed; false if only a references were
     *                       removed
     */
    public function delete()
    {
        return parent::delete();
    }
}