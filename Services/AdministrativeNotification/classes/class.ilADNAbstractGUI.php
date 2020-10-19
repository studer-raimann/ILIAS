<?php

use ILIAS\GlobalScreen\Scope\MainMenu\Collector\Renderer\Hasher;

/**
 * Class ilADNAbstractGUI
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class ilADNAbstractGUI
{
    const IDENTIFIER = 'identifier';
    use Hasher;

    /**
     * @var \ILIAS\DI\UIServices
     */
    protected $ui;
    /**
     * @var \ILIAS\DI\HTTPServices
     */
    protected $http;
    /**
     * @var ilToolbarGUI
     */
    protected $toolbar;
    /**
     * @var ilADNTabHandling
     */
    protected $tab_handling;
    /**
     * @var ilTabsGUI
     */
    protected $tabs;
    /**
     * @var ilLanguage
     */
    public $lng;
    /**
     * @var ilCtrl
     */
    protected $ctrl;
    /**
     * @var ilTemplate
     */
    public $tpl;
    /**
     * @var ilTree
     */
    public $tree;
    /**
     * @var ilObjMainMenuAccess
     */
    protected $access;

    /**
     * ilADNAbstractGUI constructor.
     * @param ilADNTabHandling $tab_handling
     */
    public function __construct(ilADNTabHandling $tab_handling)
    {
        global $DIC;

        $this->tab_handling = $tab_handling;
        $this->tabs         = $DIC['ilTabs'];
        $this->lng          = $DIC->language();
        $this->ctrl         = $DIC['ilCtrl'];
        $this->tpl          = $DIC['tpl'];
        $this->tree         = $DIC['tree'];
        $this->toolbar      = $DIC['ilToolbar'];
        $this->http         = $DIC->http();
        $this->ui           = $DIC->ui();
        $this->access       = new ilObjAdministrativeNotificationAccess();

        $this->lng->loadLanguageModule('form');
    }

    /**
     * @param string|null $standard
     * @return string
     * @throws ilException
     */
    protected function determineCommand(string $standard = null) : ?string
    {
        $this->access->checkAccessAndThrowException('visible,read');
        $cmd = $this->ctrl->getCmd();
        if ($cmd !== '') {
            return $cmd;
        }

        return $standard;
    }

    /**
     * @return ilMMItemFacadeInterface
     * @throws Throwable
     */
    protected function getMMItemFromRequest() : ilMMItemFacadeInterface
    {
        $r    = $this->http->request();
        $get  = $r->getQueryParams();
        $post = $r->getParsedBody();

        if (!isset($post['cmd']) && isset($post['interruptive_items'])) {
            $string         = $post['interruptive_items'][0];
            $identification = $this->unhash($string);
        } else {
            $identification = $this->unhash($get[self::IDENTIFIER]);
        }

        return $this->repository->getItemFacadeForIdentificationString($identification);
    }

    abstract protected function dispatchCommand(string $cmd) : string;

    public function executeCommand() : void
    {
        $next_class = $this->ctrl->getNextClass();

        if ($next_class === '') {
            $cmd = $this->determineCommand();
            $this->tpl->setContent($this->dispatchCommand($cmd));

            return;
        }

        switch ($next_class) {
            case strtolower(ilMMItemTranslationGUI::class):
                $this->tab_handling->initTabs(ilObjMainMenuGUI::TAB_MAIN, self::CMD_VIEW_TOP_ITEMS, true);
                $g = new ilMMItemTranslationGUI($this->getMMItemFromRequest(), $this->repository);
                $this->ctrl->forwardCommand($g);
                break;
            default:
                break;
        }
    }

    public function renderInterruptiveModal()
    {
        $f = $this->ui->factory();
        $r = $this->ui->renderer();

        $form_action  = $this->ctrl->getFormActionByClass(self::class, self::CMD_DELETE);
        $delete_modal = $f->modal()->interruptive(
            $this->lng->txt("delete"),
            $this->lng->txt(self::CMD_CONFIRM_DELETE),
            $form_action
        );

        echo $r->render([$delete_modal]);
        exit;
    }
}
