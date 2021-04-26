<?php
declare(strict_types=1);

namespace ILIAS\Modules\EmployeeTalk\Talk\DAO;

use ilDateTime;

final class EmployeeTalk
{
    /**
     * @var int $objectId
     */
    private $objectId;

    /**
     * @var ilDateTime $startDate
     */
    private $startDate;

    /**
     * @var ilDateTime $endDate
     */
    private $endDate;

    /**
     * @var bool $allDay
     */
    private $allDay;

    /**
     * @var string $seriesId
     */
    private $seriesId;

    /**
     * @var string $location
     */
    private $location;

    /**
     * @var int $employee
     */
    private $employee;

    /**
     * @var bool $completed
     */
    private $completed;

    /**
     * @var bool $standalone
     */
    private $standalone;

    /**
     * EmployeeTalk constructor.
     * @param int $objectId
     * @param ilDateTime $startDate
     * @param ilDateTime $endDate
     * @param bool $allDay
     * @param string $seriesId
     * @param string $location
     * @param int $employee
     * @param bool $completed
     * @param bool $standalone
     */
    public function __construct(
        int $objectId,
        ilDateTime $startDate,
        ilDateTime $endDate,
        bool $allDay,
        string $seriesId,
        string $location,
        int $employee,
        bool $completed,
        bool $standalone
    ) {
        $this->objectId = $objectId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->allDay = $allDay;
        $this->seriesId = $seriesId;
        $this->location = $location;
        $this->employee = $employee;
        $this->completed = $completed;
        $this->standalone = $standalone;
    }

    /**
     * @return int
     */
    public function getObjectId() : int
    {
        return $this->objectId;
    }

    /**
     * @param int $objectId
     * @return EmployeeTalk
     */
    public function setObjectId(int $objectId) : EmployeeTalk
    {
        $this->objectId = $objectId;
        return $this;
    }

    /**
     * @return ilDateTime
     */
    public function getStartDate() : ilDateTime
    {
        return $this->startDate;
    }

    /**
     * @param ilDateTime $startDate
     * @return EmployeeTalk
     */
    public function setStartDate(ilDateTime $startDate) : EmployeeTalk
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return ilDateTime
     */
    public function getEndDate() : ilDateTime
    {
        return $this->endDate;
    }

    /**
     * @param ilDateTime $endDate
     * @return EmployeeTalk
     */
    public function setEndDate(ilDateTime $endDate) : EmployeeTalk
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAllDay() : bool
    {
        return $this->allDay;
    }

    /**
     * @param bool $allDay
     * @return EmployeeTalk
     */
    public function setAllDay(bool $allDay) : EmployeeTalk
    {
        $this->allDay = $allDay;
        return $this;
    }

    /**
     * @return string
     */
    public function getSeriesId() : string
    {
        return $this->seriesId;
    }

    /**
     * @param string $seriesId
     * @return EmployeeTalk
     */
    public function setSeriesId(string $seriesId) : EmployeeTalk
    {
        $this->seriesId = $seriesId;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocation() : string
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return EmployeeTalk
     */
    public function setLocation(string $location) : EmployeeTalk
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return int
     */
    public function getEmployee() : int
    {
        return $this->employee;
    }

    /**
     * @param int $employee
     * @return EmployeeTalk
     */
    public function setEmployee(int $employee) : EmployeeTalk
    {
        $this->employee = $employee;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCompleted() : bool
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     * @return EmployeeTalk
     */
    public function setCompleted(bool $completed) : EmployeeTalk
    {
        $this->completed = $completed;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStandalone() : bool
    {
        return $this->standalone;
    }

    /**
     * @param bool $standalone
     * @return EmployeeTalk
     */
    public function setStandalone(bool $standalone) : EmployeeTalk
    {
        $this->standalone = $standalone;
        return $this;
    }


}