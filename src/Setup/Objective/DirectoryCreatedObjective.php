<?php declare(strict_types=1);

/* Copyright (c) 2019 Richard Klees <richard.klees@concepts-and-training.de>, Fabian Schmid <fs@studer-raimann.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\Setup\Objective;

use ILIAS\Setup;

/**
 * Create a directory.
 */
class DirectoryCreatedObjective implements Setup\Objective
{
    const DEFAULT_DIRECTORY_PERMISSIONS = 0755;

    protected string $path;
    protected int $permissions;

    public function __construct(
        string $path,
        int $permissions = self::DEFAULT_DIRECTORY_PERMISSIONS
    ) {
        if ($path == "") {
            throw new \InvalidArgumentException(
                "Path is empty."
            );
        }
        $this->path = $path;
        $this->permissions = $permissions;
    }

    /**
     * Uses hashed Path.
     *
     * @inheritdocs
     */
    public function getHash() : string
    {
        return hash("sha256", self::class . "::" . $this->path);
    }

    /**
     * Defaults to "Build $this->getArtifactPath()".
     *
     * @inheritdocs
     */
    public function getLabel() : string
    {
        return "Create directory '$this->path'";
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
     * @inheritdocs
     */
    public function getPreconditions(Setup\Environment $environment) : array
    {
        if (file_exists($this->path)) {
            return [];
        }
        return [
            new Setup\Condition\CanCreateDirectoriesInDirectoryCondition(dirname($this->path))
        ];
    }

    /**
     * @inheritdocs
     */
    public function achieve(Setup\Environment $environment) : Setup\Environment
    {
        mkdir($this->path, $this->permissions);

        if (!is_dir($this->path)) {
            throw new Setup\UnachievableException(
                "Could not create directory '$this->path'"
            );
        }
        return $environment;
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(Setup\Environment $environment) : bool
    {
        return !file_exists($this->path);
    }
}
