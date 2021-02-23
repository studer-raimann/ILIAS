<?php
declare(strict_types=1);

use ILIAS\EmployeeTalk\UI\ControlFlowCommand;

/**
 * Class ilTalkTemplateListGUI
 *
 * @author            Nicolas Schäfli <ns@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy ilTalkTemplateListGUI: ilEmployeeTalkGUI
 * @ilCtrl_Calls      ilTalkTemplateListGUI: ilFormPropertyDispatchGUI
 */
final class ilTalkTemplateListGUI
{
    /**
     * @var ilCtrl $controlFlow
     */
    private $controlFlow;

    public function __construct(ilCtrl $controlFlow)
    {
        $this->controlFlow = $controlFlow;
    }

    function executeCommand(): void {
        $command = $this->controlFlow->getCmd(ControlFlowCommand::DEFAULT);

        switch($command) {
            case ControlFlowCommand::CREATE:
                $this->create();
                break;
            case ControlFlowCommand::UPDATE:
                $this->update();
                break;
            case ControlFlowCommand::DELETE:
                $this->delete();
                break;
            default:
                $this->index();
                break;
        }
    }

    private function index(): void {

    }

    private function create(): void {

    }

    private function update(): void {

    }

    private function delete(): void {

    }
}