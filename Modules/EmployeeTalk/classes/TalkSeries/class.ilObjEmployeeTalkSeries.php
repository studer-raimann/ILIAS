<?php
declare(strict_types=1);

use ILIAS\Modules\EmployeeTalk\Talk\Repository\EmployeeTalkRepository;
use ILIAS\Modules\EmployeeTalk\Talk\DAO\EmployeeTalk;

final class ilObjEmployeeTalkSeries extends ilContainer
{
    const TYPE = 'tals';

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


        //TODO: Copy metadata from template
        parent::create();

        $this->_writeContainerSetting($this->getId(), ilObjectServiceSettingsGUI::CUSTOM_METADATA, true);

        /**
         * @var \ILIAS\DI\Container $container
         */
        $container = $GLOBALS['DIC'];

        $container->event()->raise(
            'Modules/EmployeeTalk',
            'create',
            ['object' => $this,
             'obj_id' => $this->getId(),
             'appointments' => []
            ]
        );
    }



    public function update()
    {
        parent::update();

        /**
         * @var \ILIAS\DI\Container $container
         */
        $container = $GLOBALS['DIC'];

        $container->event()->raise(
            'Modules/EmployeeTalk',
            'update',
            ['object' => $this,
                  'obj_id' => $this->getId(),
                  'appointments' => []
            ]
        );
    }

    /**
     * @param        $a_id
     * @param bool   $a_reference
     * @param string $type
     * @return bool
     */
    public static function _exists($a_id, $a_reference = false, $type = null)
    {
        return parent::_exists($a_id, $a_reference, self::TYPE);
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
            [
                'object' => $this,
                'obj_id' => $this->getId(),
                'appointments' => []
            ]
        );

        return parent::delete();
    }

}