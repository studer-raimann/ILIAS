<?php
declare(strict_types=1);

final class ilObjEmployeeTalk extends ilObject
{
    const TABLE_NAME = 'etal_data';
    /**
     * @var int
     */
    private static $root_ref_id;
    /**
     * @var int
     */
    private static $root_id;

    /**
     * @var ilDateTime | null $startDate
     */
    private $startDate;

    /**
     * @var ilDateTime | null $endDate
     */
    private $endDate;

    /**
     * @param int  $a_id
     * @param bool $a_call_by_reference
     */
    public function __construct($a_id = 0, $a_call_by_reference = true)
    {
        $this->type = "etal";
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

        /*$app = new ilCalendarAppointmentTemplate(1);
        $app->setTitle($this->getTitle());
        $app->setSubtitle('etal_cal_meeting');
        $app->setTranslationType(IL_CAL_TRANSLATION_SYSTEM);
        $app->setDescription($this->getLongDescription());
        $app->setStart($this->startDate);
        $app->setEnd($this->endDate);
        $apps[] = $app; */

        /**
         * @var \ILIAS\DI\Container $container
         */
        $container = $GLOBALS['DIC'];

        /*$container->event()->raise(
            'Modules/EmployeeTalk',
            'create',
            ['object' => $this,
                  'obj_id' => $this->getId(),
                  'appointments' => $apps
            ]
        );*/
    }

    public function update()
    {
        parent::update();

        /*$app = new ilCalendarAppointmentTemplate(1);
        $app->setTitle($this->getTitle());
        $app->setSubtitle('etal_cal_meeting');
        $app->setTranslationType(IL_CAL_TRANSLATION_SYSTEM);
        $app->setDescription($this->getLongDescription());
        $app->setStart($this->startDate);
        $app->setEnd($this->endDate);
        $apps[] = $app; */

        /**
         * @var \ILIAS\DI\Container $container
         */
        $container = $GLOBALS['DIC'];

        /*$container->event()->raise(
            'Modules/EmployeeTalk',
            'update',
            ['object' => $this,
                  'obj_id' => $this->getId(),
                  'appointments' => $apps
            ]
        );*/
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

    public function getParent() : ilObjTalkTemplate
    {
        return new ilObjTalkTemplate($this->tree->getParentId($this->ref_id), true);
    }

    /**
     * @param        $a_id
     * @param bool   $a_reference
     * @param string $type
     * @return bool
     */
    public static function _exists($a_id, $a_reference = false, $type = null)
    {
        return parent::_exists($a_id, $a_reference, "etal");
    }

    /**
     * delete orgunit, childs and all related data
     * @return    boolean    true if all object data were removed; false if only a references were
     *                       removed
     */
    public function delete()
    {
        /**
         * @var \ILIAS\DI\Container $container
         */
        $container = $GLOBALS['DIC'];

        /*$container->event()->raise(
            'Modules/EmployeeTalk',
            'create',
            ['object' => $this,
                  'obj_id' => $this->getId(),
                  'appointments' => []
            ]
        );*/

        return parent::delete();
    }
}