<?php

/* Copyright (c) 1998-2021 ILIAS open source, GPLv3, see LICENSE */

/**
 * Object not found exception
 *
 * @author Alex Killing <alex.killing@gmx.de>
 */
class ilObjectNotFoundException extends ilObjectException
{
    /**
     * Constructor
     *
     * A message is not optional as in build in class Exception
     *
     * @param string $a_message message
     */
    public function __construct($a_message)
    {
        parent::__construct($a_message);
    }
}
