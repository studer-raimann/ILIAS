<?php
declare(strict_types=1);

use ILIAS\EmployeeTalk\UI\ControlFlowCommand;

final class ilObjTalkTemplateAccess extends ilObjectAccess
{
    /**
     * get commands
     *
     * this method returns an array of all possible commands/permission combinations
     *
     * example:
     * $commands = array
     *    (
     *        array('permission' => 'read', 'cmd' => 'view', 'lang_var' => 'show'),
     *        array('permission' => 'write', 'cmd' => 'edit', 'lang_var' => 'edit'),
     *    );
     */
    public static function _getCommands() : array
    {
        $commands = [
            [
                'permission' => 'read',
                'cmd' => ControlFlowCommand::DEFAULT,
                'lang_var' => 'show',
                'default' => true,
            ]
        ];

        return $commands;
    }



    /**
     * @param string $a_target check whether goto script will succeed
     *
     * @return bool
     */
    public static function _checkGoto($a_target) : bool
    {
        global $DIC;

        $t_arr = explode('_', $a_target);
        if ($t_arr[0] !== 'talt' || ((int) $t_arr[1]) <= 0) {
            return false;
        }
        if ($DIC->access()->checkAccess('read', '', $t_arr[1])) {
            return true;
        }

        return false;
    }
}