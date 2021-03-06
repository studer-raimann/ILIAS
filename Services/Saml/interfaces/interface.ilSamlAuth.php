<?php declare(strict_types=1);
/* Copyright (c) 1998-2017 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Interface ilSamlAuth
 * @author Michael Jansen <mjansen@databay.de>
 */
interface ilSamlAuth
{
    /**
     * @return string
     */
    public function getAuthId() : string;

    /**
     * Protect a script resource with a SAML auth.
     */
    public function protectResource() : void;

    /**
     * @param string $key
     * @param mixed $value
     */
    public function storeParam(string $key, $value) : void;

    /**
     * @return bool
     */
    public function isAuthenticated() : bool;

    /**
     * @param string $key
     * @return mixed
     */
    public function popParam(string $key);

    /**
     * @param string $key
     * @return mixed
     */
    public function getParam(string $key);

    /**
     * @return array
     */
    public function getAttributes() : array;

    /**
     * @param string $returnUrl
     */
    public function logout(string $returnUrl = '') : void;

    /**
     * @return ilSamlIdpDiscovery
     */
    public function getIdpDiscovery() : ilSamlIdpDiscovery;

    /**
     * @return array
     */
    public function getAuthDataArray() : array;
}
