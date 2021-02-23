<?php
declare(strict_types=1);

namespace ILIAS\EmployeeTalk\UI;

interface ControlFlowCommand
{
    const DEFAULT = "view";
    const INDEX = "view";

    const CREATE_INDEX = "create_index";
    const CREATE = "create";

    const UPDATE_INDEX = "update_index";
    const UPDATE = "edit";

    const DELETE_INDEX = "delete_index";
    const DELETE = "delete";
}