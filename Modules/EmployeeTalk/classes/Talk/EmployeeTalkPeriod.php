<?php
declare(strict_types=1);

namespace ILIAS\Modules\EmployeeTalk\Talk;

use ilDatePeriod;
use ilDateTime;

final class EmployeeTalkPeriod implements ilDatePeriod
{
    /**
     * @var ilDateTime $start
     */
    private $start;
    /**
     * @var ilDateTime $end
     */
    private $end;
    /**
     * @var bool $fullDay
     */
    private $fullDay;

    /**
     * EmployeeTalkPeriod constructor.
     * @param ilDateTime $start
     * @param ilDateTime $end
     * @param bool       $fullDay
     */
    public function __construct(ilDateTime $start, ilDateTime $end, bool $fullDay)
    {
        $this->start = $start;
        $this->end = $end;
        $this->fullDay = $fullDay;
    }

    public function getStart(): ilDateTime
    {
        return $this->start;
    }

    public function getEnd(): ilDateTime
    {
        return $this->end;
    }

    public function isFullday(): bool
    {
        return $this->fullDay;
    }
}