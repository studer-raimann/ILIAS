<?php


/* Copyright (c) 2019 Richard Klees <richard.klees@concepts-and-training.de>, Fabian Schmid <fs@studer-raimann.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\Setup\Migration;

use ILIAS\Setup;

/**
 * This is an objective to handle large migrations.
 */
abstract class MigrationObjective implements Setup\Objective
{
    /**
     * Get an ID for the migration you want to start. IDs of migration should be
     * speaky and unique, although it gets a unique addition internally as well.
     */
    abstract public function getMigrationIdentification() : string;


    /**
     * Processes the migration with the given en.
     *
     * If you want to reimplement this, you most probably also want to reimplement
     * `getPreconditions` to prepare the environment properly.
     */
    public function processMigration(Setup\Environment $env) : Setup\Artifact
    {
        return $this->build();
    }

    /**
     * Defaults to no preconditions.
     *
     * @inheritdocs
     */
    public function getPreconditions(Setup\Environment $environment) : array
    {
        return [];
    }

    /**
     * Uses hashed Path.
     *
     * @inheritdocs
     */
    public function getHash() : string
    {
        return hash("sha256", $this->getMigrationIdentification());
    }

    /**
     * Defaults to "Start Migration $this->getMigrationIdentification()".
     *
     * @inheritdocs
     */
    public function getLabel() : string
    {
        return 'Start Migration ' . $this->getMigrationIdentification();
    }

    /**
     * Defaults to 'true'.
     *
     * @inheritdocs
     */
    public function isNotable() : bool
    {
        return true;
    }

    /**
     * Builds the artifact and puts it in its location.
     *
     * @inheritdocs
     */
    public function achieve(Setup\Environment $environment) : Setup\Environment
    {
        // maybe we dont need achieve here, but this should only start the migration in some way

//
//
        $artifact = $this->buildIn($environment);
//
//        // TODO: Do we want to configure this?
//        $base_path = getcwd();
//        $path = $base_path . "/" . $this->getMigrationIdentification();
//
//        $this->makeDirectoryFor($path);
//
//        file_put_contents($path, $artifact->serialize());

        return $environment;
    }

    public function isApplicable(Setup\Environment $environment) : bool
    {
        return true;
    }
}
