<?php

/* Copyright (c) 1998-2021 ILIAS open source, GPLv3, see LICENSE */

/**
 * Caches (check) access information on list items.
 *
 * @author Alex Killing <alex.killing@gmx.de>
 */
class ilListItemAccessCache extends ilCache
{
    /**
     * @var ilSetting
     */
    protected $settings;

    public static $disabled = false;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        global $DIC;

        $this->settings = $DIC->settings();
        parent::__construct("ServicesObject", "CheckAccess", false);
        $this->setExpiresAfter(0);
        self::$disabled = true;
    }
    
    /**
     * Check if cache is disabled
     * @return
     */
    public function isDisabled()
    {
        return self::$disabled or parent::isDisabled();
    }
    
    
    /**
     * Read an entry
     */
    protected function readEntry($a_id)
    {
        if (!$this->isDisabled()) {
            return parent::readEntry($a_id);
        }
        return false;
    }
    
    
    /**
     * Id is user_id:ref_id, we store ref_if additionally
     */
    public function storeEntry(
        $a_id,
        $a_value,
        $a_int_key1 = 0,
        $a_int_key2 = null,
        $a_text_key1 = null,
        $a_text_key2 = null
    ) {
        if (!$this->isDisabled()) {
            parent::storeEntry($a_id, $a_value, $a_int_key1);
        }
    }

    /**
     * This one can be called, e.g.
     */
    public function deleteByRefId($a_ref_id)
    {
        parent::deleteByAdditionalKeys($a_ref_id);
    }
}
