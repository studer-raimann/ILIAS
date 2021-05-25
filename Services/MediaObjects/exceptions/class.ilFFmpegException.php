<?php

/* Copyright (c) 1998-2021 ILIAS open source, GPLv3, see LICENSE */

/**
 * Class for ffmpeg exception handling in ILIAS.
 *
 * @author Alex Killing <alex.killing@gmx.de>
 */
class ilFFmpegException extends ilException
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
