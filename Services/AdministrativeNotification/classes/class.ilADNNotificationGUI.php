<?php

/**
 * Class ilADNNotificationGUI
 * @ilCtrl_IsCalledBy ilADNNotificationGUI: ilObjAdministrativeNotificationGUI
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 */
class ilADNNotificationGUI extends ilADNAbstractGUI
{

    protected function dispatchCommand($cmd) : string
    {
        global $DIC;
        switch ($cmd) {
            default:
                return '';
        }

        return "";
    }

}
