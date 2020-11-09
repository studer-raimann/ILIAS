<?php

use ILIAS\UI\NotImplementedException;

/**
 * Class ilObjFileImplementationAbstract
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class ilObjFileImplementationAbstract implements ilObjFileImplementationInterface
{

    /**
     * @inheritDoc
     */
    public function createDirectory()
    {
        // noting to do
    }

    /**
     * @inheritDoc
     */
    public function replaceFile($a_upload_file, $a_filename)
    {
        // TODO: Implement replaceFile() method.
    }

    /**
     * @inheritDoc
     */
    public function addFileVersion($a_upload_file, $a_filename)
    {
        // TODO: Implement addFileVersion() method.
    }

    /**
     * @inheritDoc
     */
    public function clearDataDirectory()
    {
        // noting to do here
    }

    /**
     * @inheritDoc
     */
    public function setFileType($a_type)
    {
        throw new NotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getFileType()
    {
        throw new NotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function setFileSize($a_size)
    {
        throw new NotImplementedException();
    }

    public function getFileSize()
    {
        throw new NotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getFile($a_hist_entry_id = null)
    {
        throw new NotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function setVersion($a_version)
    {
        throw new NotImplementedException();
    }

    public function getVersion()
    {
        throw new NotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function setMaxVersion($a_max_version)
    {
        throw new NotImplementedException();
    }

    public function getMaxVersion()
    {
        throw new NotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function storeUnzipedFile($a_upload_file, $a_filename)
    {
        throw new NotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getSpecificVersion($version_id)
    {
        throw new NotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function rollback($version_id)
    {
        throw new NotImplementedException();
    }

}
