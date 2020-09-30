<?php
/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

namespace ILIAS\Setup\CLI;

use ILIAS\Setup\Agent;
use ILIAS\Setup\AgentCollection;
use ILIAS\Setup\ArrayEnvironment;
use ILIAS\Setup\Config;
use ILIAS\Setup\Environment;
use ILIAS\Setup\Objective;
use ILIAS\Setup\ObjectiveCollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Migration command.
 */
class MigrateCommand extends BaseCommand
{
    protected static $defaultName = "migrate";

    public function configure()
    {
        parent::configure();
        $this->setDescription("Starts all needed Migrations after an update.");
    }

    protected function printIntroMessage(IOWrapper $io)
    {
        $io->title("Start migrations in ILIAS");
    }

    protected function printOutroMessage(IOWrapper $io)
    {
        $io->success("Migrations complete. Thanks and have fun!");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        return parent::execute($input, $output);
    }

    protected function buildEnvironment(Agent $agent, ?Config $config, IOWrapper $io) : Environment
    {
        $environment = new ArrayEnvironment([
            Environment::RESOURCE_ADMIN_INTERACTION => $io
        ]);

        if ($agent instanceof AgentCollection && $config) {
            foreach ($config->getKeys() as $k) {
                $environment = $environment->withConfigFor($k, $config->getConfig($k));
            }
        }

        return $environment;
    }

    protected function getObjective(Agent $agent, ?Config $config) : Objective
    {
        return new ObjectiveCollection(
            "Start migrations in ILIAS",
            false,
            $agent->getMigrateObjective($config)
        );
    }
}
