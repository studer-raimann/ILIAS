<?php

/**
 * Class ilContentStyleWAC
 *
 * @author  Alex Killing <killing@leifos.de>
 */
class ilContentStyleWAC implements ilWACCheckingClass
{

    /**
     * @param ilWACPath $ilWACPath
     *
     * @return bool
     */
    public function canBeDelivered(ilWACPath $ilWACPath)
    {
        //preg_match("/.\\/data\\/.*\\/mm_([0-9]*)\\/.*/ui", $ilWACPath->getPath(), $matches);
        return true;
    }
}
