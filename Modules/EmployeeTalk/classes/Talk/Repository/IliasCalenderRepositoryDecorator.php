<?php
declare(strict_types=1);

namespace ILIAS\Modules\EmployeeTalk\Talk\Repository;

use ILIAS\Modules\EmployeeTalk\Talk\DAO\EmployeeTalk;
use ilCalendarAppointmentTemplate;

final class IliasCalenderRepositoryDecorator implements EmployeeTalkRepository
{
    /**
     * @var EmployeeTalkRepository $repository
     */
    private $repository;

    /**
     * IliasCalenderRepositroyDecorator constructor.
     * @param EmployeeTalkRepository $repository
     */
    public function __construct(EmployeeTalkRepository $repository)
    {
        $this->repository = $repository;
    }

    function findByObjectId(int $objectId) : EmployeeTalk
    {
        return $this->repository->findByObjectId($objectId);
    }

    function findByEmployee(int $iliasUserId) : array
    {
        return $this->repository->findByEmployee($iliasUserId);
    }

    function findBySeries(string $seriesId) : array
    {
        return $this->repository->findBySeries($seriesId);
    }

    function create(EmployeeTalk $talk) : EmployeeTalk
    {
        return $this->repository->create($talk);
    }

    function update(EmployeeTalk $talk) : EmployeeTalk
    {
        return $this->repository->update($talk);
    }

    function delete(EmployeeTalk $talk) : void
    {
        $this->repository->delete($talk);
    }

}