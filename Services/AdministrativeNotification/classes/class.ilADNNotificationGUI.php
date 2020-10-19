<<?php

/**
 * Class ilADNNotificationGUI
 * @ilCtrl_IsCalledBy ilADNNotificationGUI: ilObjAdministrativeNotificationGUI
 * @ilCtrl_IsCalledBy ilADNNotificationGUI: ilObjAdministrativeNotificationGUI
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 */
class ilADNNotificationGUI extends ilADNAbstractGUI
{
    const TAB_TABLE = 'notifications';
    const CMD_DEFAULT = 'index';

    protected function dispatchCommand($cmd) : string
    {
        switch ($cmd) {
            case self::CMD_DEFAULT:
            default:
                $this->tab_handling->initTabs(ilObjAdministrativeNotificationGUI::TAB_MAIN, ilMMSubItemGUI::CMD_VIEW_SUB_ITEMS, true, self::class);
                return $this->index();

        }

        return "";
    }

    protected function index() : string
    {
        return 'hello';
    }
}
