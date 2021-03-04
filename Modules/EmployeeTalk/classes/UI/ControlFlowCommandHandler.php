<?php
declare(strict_types=1);

namespace ILIAS\EmployeeTalk\UI;

interface ControlFlowCommandHandler
{
    public function executeCommand(): bool;
}