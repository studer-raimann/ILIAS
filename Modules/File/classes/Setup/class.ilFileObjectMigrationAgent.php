<?php

/* Copyright (c) 2019 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

use ILIAS\Setup;
use ILIAS\Refinery;
use ILIAS\UI;
use ILIAS\Setup\Config;
use ILIAS\Setup\Objective;

class ilFileObjectMigrationAgent implements Setup\Agent
{
    /**
     * @var Refinery\Factory
     */
    protected $refinery;

    public function __construct(
        Refinery\Factory $refinery
    ) {
        $this->refinery = $refinery;
    }

    /**
     * @inheritdoc
     */
    public function hasConfig() : bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getConfigInput(Setup\Config $config = null) : UI\Component\Input\Field\Input
    {
        throw new \LogicException("Not yet implemented.");
    }

    /**
     * @inheritdoc
     */
    public function getArrayToConfigTransformation() : Refinery\Transformation
    {
        throw new \LogicException(self::class . " has no Config.");
    }

    /**
     * @inheritdoc
     */
    public function getInstallObjective(Setup\Config $config = null) : Setup\Objective
    {
        return new Setup\Objective\NullObjective();
    }

    /**
     * @inheritdoc
     */
    public function getUpdateObjective(Setup\Config $config = null) : Setup\Objective
    {
        return new Setup\Objective\NullObjective();
    }

    /**
     * @inheritdoc
     */
    public function getBuildArtifactObjective() : Setup\Objective
    {
        return new Setup\Objective\NullObjective();
    }

    /**
     * @inheritdoc
     */
    public function getMigrateObjective(Config $config = null) : Objective
    {
        return new class extends Setup\Migration\MigrationObjective {
            public function getMigrationIdentification() : string
            {
                return 'files';
            }

            public function build() : Setup\Artifact
            {
                echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!";
            }
        };


        return new Setup\Objective\NullObjective();
    }
}
