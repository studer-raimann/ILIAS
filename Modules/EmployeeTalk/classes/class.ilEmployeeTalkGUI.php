<?php
declare(strict_types=1);

final class ilEmployeeTalkGUI
{
    /**
     * @var \ILIAS\DI\Container $container
     */
    private $container;

    public function __construct()
    {
        $this->container = $GLOBALS["DIC"];

        $this->container->language()->loadLanguageModule('mst');
        $this->container->language()->loadLanguageModule('trac');

        // get the standard template
        $this->container->ui()->mainTemplate()->loadStandardTemplate();
        $this->container->ui()->mainTemplate()->setTitle($this->container->language()->txt('mst_my_staff'));
    }

    public function executeCommand() : void
    {
        // determine next class in the call structure
        $next_class = $this->container->ctrl()->getNextClass($this);

        switch ($next_class) {
            default:
                $list_gui = new ilTalkTemplateListGUI($this->container->ctrl());
                $this->container->ctrl()->forwardCommand($list_gui);
                break;
        }

        $this->container->ui()->mainTemplate()->printToStdout();
    }
}