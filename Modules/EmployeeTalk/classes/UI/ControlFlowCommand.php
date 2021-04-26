<?php
declare(strict_types=1);

namespace ILIAS\EmployeeTalk\UI;

interface ControlFlowCommand
{
    const DEFAULT = "view";
    const INDEX = "view";

    const CREATE = "create";
    const SAVE = "save";

    const UPDATE_INDEX = "update";
    const UPDATE = "edit";

    const DELETE_INDEX = "delete";
    const DELETE = "confirmedDelete";

    const APPLY_FILTER = 'applyFilter';
    const RESET_FILTER = 'resetFilter';
}