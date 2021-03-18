<?php
declare(strict_types=1);

use ILIAS\Modules\EmployeeTalk\Talk\Repository\EmployeeTalkRepository;
use ILIAS\Modules\EmployeeTalk\Talk\DAO\EmployeeTalk;

final class ilObjEmployeeTalk extends ilObject
{
    /**
     * @var int
     */
    private static $root_ref_id;
    /**
     * @var int
     */
    private static $root_id;

    /**
     * @var EmployeeTalkRepository $repository
     */
    private $repository;

    /**
     * @var EmployeeTalk $data
     */
    private $data;

    /**
     * @param int  $a_id
     * @param bool $a_call_by_reference
     */
    public function __construct($a_id = 0, $a_call_by_reference = true)
    {
        $this->setType('etal');

        $this->repository = new \ILIAS\Modules\EmployeeTalk\Talk\Repository\IliasDBEmployeeTalkRepository($GLOBALS['DIC']->database());
        $datetime = new ilDateTime(1, IL_CAL_UNIX);
        $this->data = new EmployeeTalk(-1, $datetime, $datetime, false, '', '', -1, false);

        parent::__construct($a_id, $a_call_by_reference);
    }

    public function read()
    {
        parent::read();
        $this->data = $this->repository->findByObjectId($this->getId());
    }

    public function create()
    {
        $this->setOfflineStatus(true);
        parent::create();

        $this->data->setObjectId($this->getId());
        $this->repository->create($this->data);

        $app = new ilCalendarAppointmentTemplate($this->getId());
        $app->setTitle($this->getTitle());
        $app->setSubtitle('etal_cal_meeting');
        $app->setTranslationType(IL_CAL_TRANSLATION_SYSTEM);
        $app->setDescription($this->getLongDescription());
        $app->setStart($this->data->getStartDate());
        $app->setEnd($this->data->getEndDate());
        $apps[] = $app;

        /**
         * @var \ILIAS\DI\Container $container
         */
        $container = $GLOBALS['DIC'];

        $container->event()->raise(
            'Modules/EmployeeTalk',
            'create',
            ['object' => $this,
             'obj_id' => $this->getId(),
             'appointments' => $apps
            ]
        );
    }

    public function update()
    {
        parent::update();
        $this->repository->update($this->data);

        $app = new ilCalendarAppointmentTemplate($this->getId());
        $app->setTitle($this->getTitle());
        $app->setSubtitle('etal_cal_meeting');
        $app->setTranslationType(IL_CAL_TRANSLATION_SYSTEM);
        $app->setDescription($this->getLongDescription());
        $app->setStart($this->data->getStartDate());
        $app->setEnd($this->data->getEndDate());
        $apps[] = $app;

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

        $container->event()->raise(
            'Modules/EmployeeTalk',
            'delete',
            ['object' => $this,
                  'obj_id' => $this->getId(),
                  'appointments' => []
            ]
        );

        return parent::delete();
    }

    /**
     * @return EmployeeTalk
     */
    public function getData() : EmployeeTalk
    {
        return clone $this->data;
    }

    /**
     * @param EmployeeTalk $data
     * @return ilObjEmployeeTalk
     */
    public function setData(EmployeeTalk $data) : ilObjEmployeeTalk
    {
        $this->data = clone $data;
        return $this;
    }
}