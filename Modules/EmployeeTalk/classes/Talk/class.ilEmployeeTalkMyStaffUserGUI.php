<?php
declare(strict_types=1);

use ILIAS\MyStaff\ilMyStaffAccess;
use Psr\Http\Message\RequestInterface;
use ILIAS\EmployeeTalk\UI\ControlFlowCommandHandler;
use ILIAS\EmployeeTalk\UI\ControlFlowCommand;
use ILIAS\Modules\EmployeeTalk\Talk\Repository\EmployeeTalkRepository;

/**
 * Class ilEmployeeTalkMyStaffUserGUI
 *
 * @author            Nicolas Schaefli <ns@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy ilEmployeeTalkMyStaffUserGUI: ilMStShowUserGUI
 * @ilCtrl_IsCalledBy ilEmployeeTalkMyStaffUserGUI: ilFormPropertyDispatchGUI
 */
final class ilEmployeeTalkMyStaffUserGUI implements ControlFlowCommandHandler
{
    /**
     * @var int
     */
    private $usrId;
    /**
     * @var ilMyStaffAccess
     */
    private $access;
    /**
     * @var ilCtrl $ctrl
     */
    private $ctrl;
    /**
     * @var ilLanguage $language
     */
    private $language;
    /**
     * @var RequestInterface $request
     */
    private $request;
    /**
     * @var ilGlobalTemplateInterface $template
     */
    private $template;
    /**
     * @var ilTabsGUI $tabs
     */
    private $tabs;
    /**
     * @var EmployeeTalkRepository $repository
     */
    private $repository;

    /**
     * ilEmployeeTalkMyStaffUserGUI constructor.
     * @param ilMyStaffAccess           $access
     * @param ilCtrl                    $ctrl
     * @param ilLanguage                $language
     * @param RequestInterface          $request
     * @param ilGlobalTemplateInterface $template
     * @param ilTabsGUI                 $tabs
     * @param EmployeeTalkRepository    $repository
     */
    public function __construct(
        ilMyStaffAccess $access,
        ilCtrl $ctrl,
        ilLanguage $language,
        RequestInterface $request,
        ilGlobalTemplateInterface $template,
        ilTabsGUI $tabs,
        EmployeeTalkRepository $repository
    ) {
        $this->access = $access;
        $this->ctrl = $ctrl;
        $this->language = $language;
        $this->request = $request;
        $this->template = $template;
        $this->tabs = $tabs;
        $this->repository = $repository;

        $this->usrId = $this->request->getQueryParams()['usr_id'];
        $this->ctrl->setParameter($this, 'usr_id', $this->usrId);
    }

    /**
     *
     */
    private function checkAccessOrFail(): void
    {
        if (!$this->usrId) {
            ilUtil::sendFailure($this->language->txt("permission_denied"), true);
            $this->ctrl->redirectByClass(ilDashboardGUI::class, "");
        }

        if (!$this->access->hasCurrentUserAccessToMyStaff() || !$this->access->hasCurrentUserAccessToUser($this->usrId)) {
            ilUtil::sendFailure($this->language->txt("permission_denied"), true);
            $this->ctrl->redirectByClass(ilDashboardGUI::class, "");
        }
    }


    /**
     *
     */
    public function executeCommand(): bool
    {
        $this->checkAccessOrFail();

        $cmd = $this->ctrl->getCmd();
        $nextClass = $this->ctrl->getNextClass();

        switch ($nextClass) {
            case strtolower(ilObjEmployeeTalkGUI::class):
                $gui = new ilObjEmployeeTalkGUI();
                $this->ctrl->redirect($gui, ControlFlowCommand::INDEX);
                break;
            case strtolower(ilFormPropertyDispatchGUI::class):
                $this->ctrl->setReturn($this, ControlFlowCommand::INDEX);
                $table = new ilEmployeeTalkTableGUI($this, ControlFlowCommand::INDEX);
                $table->executeCommand();
                break;
            default:
                switch ($cmd) {
                    case ControlFlowCommand::INDEX:
                        $this->view();
                        break;
                    case ControlFlowCommand::APPLY_FILTER:
                        $this->applyFilter();
                        break;
                    case ControlFlowCommand::RESET_FILTER:
                        $this->resetFilter();
                        break;
                    default:
                        $this->ctrl->redirectByClass(ilDashboardGUI::class, "");
                        break;
                }
        }

        return true;
    }

    private function applyFilter(): void
    {
        $table = new ilEmployeeTalkTableGUI($this, ControlFlowCommand::APPLY_FILTER);
        $table->writeFilterToSession();
        $table->resetOffset();
        $this->view();
    }


    private function resetFilter(): void
    {
        $table = new ilEmployeeTalkTableGUI($this, ControlFlowCommand::RESET_FILTER);
        $table->resetOffset();
        $table->resetFilter();
        $this->view();
    }

    private function view(): void
    {
        $table = new ilEmployeeTalkTableGUI($this, ControlFlowCommand::INDEX);

        $talks = $this->repository->findByEmployee(intval($this->usrId));
        $table->setTalkData($talks);
        $this->template->setContent($table->getHTML());
    }
}