<?php

/* Copyright (c) 1998-2021 ILIAS open source, GPLv3, see LICENSE */

/**
 * Class ilContextWAC
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ilContextWAC implements ilContextTemplate
{

    /**
     * @return bool
     */
    public static function supportsRedirects()
    {
        return false;
    }


    /**
     * @return bool
     */
    public static function hasUser()
    {
        return true;
    }


    /**
     * @return bool
     */
    public static function usesHTTP()
    {
        return true;
    }


    /**
     * @return bool
     */
    public static function hasHTML()
    {
        return true;
    }


    /**
     * @return bool
     */
    public static function usesTemplate()
    {
        return true;
    }


    /**
     * @return bool
     */
    public static function initClient()
    {
        return true;
    }


    /**
     * @return bool
     */
    public static function doAuthentication()
    {
        return true;
    }

    /**
     * Check if persistent session handling is supported
     * @return boolean
     */
    public static function supportsPersistentSessions()
    {
        return true;
    }
    
    /**
     * Supports push messages
     *
     * @return bool
     */
    public static function supportsPushMessages()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public static function isSessionMainContext()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public static function modifyHttpPath(string $httpPath) : string
    {
        return $httpPath;
    }
}
