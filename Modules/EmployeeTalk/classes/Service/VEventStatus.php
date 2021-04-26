<?php

namespace ILIAS\EmployeeTalk\Service;

/**
 * Interface VEventStatus
 *
 * Defines all valid vevent status.
 *
 * @package ILIAS\EmployeeTalk\Service
 */
interface VEventStatus
{
    const TENTATIVE = "TENTATIVE";
    const CONFIRMED = "CONFIRMED";
    const CANCELLED = "CANCELLED";
}