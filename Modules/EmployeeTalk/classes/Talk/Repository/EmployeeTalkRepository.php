<?php
declare(strict_types=1);

namespace ILIAS\Modules\EmployeeTalk\Talk\Repository;

use ILIAS\Modules\EmployeeTalk\Talk\DAO\EmployeeTalk;

interface EmployeeTalkRepository
{
    function findByObjectId(int $objectId): EmployeeTalk;

    /**
     * @param int $iliasUserId
     *
     * @return EmployeeTalk[]
     */
    function findByEmployee(int $iliasUserId): array;

    /**
     * @param string $seriesId
     *
     * @return EmployeeTalk[]
     */
    function findBySeries(string $seriesId): array;
    function create(EmployeeTalk $talk): EmployeeTalk;
    function update(EmployeeTalk $talk): EmployeeTalk;
    function delete(EmployeeTalk $talk): void;
    /**
     * @param int[] $employees
     *
     * @return EmployeeTalk[]
     */
    function findByEmployees(array $employees): array;

    /**
     * @param int[] $employees
     * @param int   $owner
     * @return EmployeeTalk[]
     */
    function findByEmployeesAndOwner(array $employees, int $owner): array;

    /**
     * @return EmployeeTalk[]
     */
    function findAll(): array;
}