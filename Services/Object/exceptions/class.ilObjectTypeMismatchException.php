<?php

/* Copyright (c) 1998-2021 ILIAS open source, GPLv3, see LICENSE */

/**
 * Type mismatch exception
 *
 * @author Alex Killing <alex.killing@gmx.de>
 */
class ilObjectTypeMismatchException extends ilObjectException
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
