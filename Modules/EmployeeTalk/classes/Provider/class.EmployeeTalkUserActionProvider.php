<?php
declare(strict_types=1);

use ILIAS\DI\Container;
use ILIAS\EmployeeTalk\UI\ControlFlowCommand;

final class EmployeeTalkUserActionProvider extends ilUserActionProvider
{
    const JUMP_TO_USER_TALK_LIST = 'etal_jump_to_user_talks';

    /**
     * @var ilLanguage $language
     */
    private $language;
    /**
     * @var ilCtrl $controlFlow
     */
    private $controlFlow;

    public function __construct()
    {
        parent::__construct();

        /**
         * @var Container $container
         */
        $container = $GLOBALS['DIC'];
        $this->language = $container->language();
        $this->controlFlow = $container->ctrl();

        $this->language->loadLanguageModule('etal');
    }

    public function collectActionsForTargetUser($a_target_user) : ilUserActionCollection
    {
        $actions = ilUserActionCollection::getInstance();

        $jumpToUserTalkList = new ilUserAction();
        $jumpToUserTalkList->setType(self::JUMP_TO_USER_TALK_LIST);
        $jumpToUserTalkList->setText($this->language->txt('mm_org_etal'));
        $jumpToUserTalkList->setHref($this->controlFlow->getLinkTargetByClass([
            strtolower(ilDashboardGUI::class),
            strtolower(ilMyStaffGUI::class),
            strtolower(ilMStShowUserGUI::class),
            strtolower(ilEmployeeTalkMyStaffUserGUI::class),
        ], ControlFlowCommand::INDEX) . "&usr_id=$a_target_user");

        $actions->addAction($jumpToUserTalkList);

        return $actions;
    }

    public function getComponentId() : string
    {
        return "etal";
    }

    public function getActionTypes() : array
    {
        return [
            self::JUMP_TO_USER_TALK_LIST => $this->language->txt('mm_org_etal')
        ];
    }
}