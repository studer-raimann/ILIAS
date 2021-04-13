<?php
declare(strict_types=1);

namespace ILIAS\Modules\EmployeeTalk\Talk\Repository;

use ILIAS\Modules\EmployeeTalk\Talk\DAO\EmployeeTalk;
use ilDBInterface;
use ilDateTime;
use ilTimeZone;

final class IliasDBEmployeeTalkRepository implements EmployeeTalkRepository
{
    /**
     * @var ilDBInterface $database
     */
    private $database;

    /**
     * IliasDBEmployeeTalkRepository constructor.
     * @param ilDBInterface $database
     */
    public function __construct(ilDBInterface $database)
    {
        $this->database = $database;
    }

    function findByEmployees(array $employees): array {
        $statement = $this->database->prepare('SELECT * FROM etal_data AS talk 
            WHERE ' . $this->database->in('employee', $employees, false, "integer")
            );
        $statement = $statement->execute();
        $talks = [];
        while (($result = $statement->fetchObject()) !== false) {
            $talks[] = $this->parseFromStdClass($result);
        }

        $this->database->free($statement);

        return $talks;
    }

    function findByObjectId(int $objectId) : EmployeeTalk
    {
        $statement = $this->database->prepare('SELECT * FROM etal_data WHERE object_id=?;', ["integer"]);
        $statement = $statement->execute([$objectId]);
        $result = $statement->fetchObject();
        $this->database->free($statement);

        //TODO raise exception if result count is 0 or greater 1

        return $this->parseFromStdClass($result);
    }

    function create(EmployeeTalk $talk) : EmployeeTalk
    {
        $this->database->insert('etal_data', [
            'object_id'             => ['int', $talk->getObjectId()],
            'series_id'             => ['text', $talk->getSeriesId()],
            'start_date'            => ['int', $talk->getStartDate()->getUnixTime()],
            'end_date'              => ['int', $talk->getEndDate()->getUnixTime()],
            'all_day'               => ['int', $talk->isAllDay()],
            'location'              => ['text', $talk->getLocation()],
            'employee'              => ['int', $talk->getEmployee()],
            'completed'             => ['int', $talk->isCompleted()],
            'standalone_date'       => ['int', $talk->isStandalone()]
            ]);

        return $talk;
    }

    function update(EmployeeTalk $talk) : EmployeeTalk
    {
        $this->database->update('etal_data', [
            'series_id'             => ['text', $talk->getSeriesId()],
            'start_date'            => ['int', $talk->getStartDate()->getUnixTime()],
            'end_date'              => ['int', $talk->getEndDate()->getUnixTime()],
            'all_day'               => ['int', $talk->isAllDay()],
            'location'              => ['text', $talk->getLocation()],
            'employee'              => ['int', $talk->getEmployee()],
            'completed'             => ['int', $talk->isCompleted()],
            'standalone_date'       => ['int', $talk->isStandalone()]
        ], [
            'object_id'             => ['int', $talk->getObjectId()]
        ]);

        return $talk;
    }

    function delete(EmployeeTalk $talk) : void
    {
        $statement = $this->database->prepareManip('DELETE FROM etal_data WHERE object_id=?;', ["integer"]);
        $statement->execute([$talk->getObjectId()]);
        $this->database->free($statement);
    }

    function findByEmployee(int $iliasUserId) : array
    {
        $statement = $this->database->prepare('SELECT * FROM etal_data WHERE employee=?;', ["integer"]);
        $statement = $statement->execute([$iliasUserId]);

        $talks = [];
        while (($result = $statement->fetchObject()) !== false) {
            $talks[] = $this->parseFromStdClass($result);
        }

        $this->database->free($statement);

        return $talks;
    }

    function findBySeries(string $seriesId) : array
    {
        $statement = $this->database->prepare('SELECT * FROM etal_data WHERE series_id=?;', ["text"]);
        $statement = $statement->execute([$seriesId]);

        $talks = [];
        while (($result = $statement->fetchObject()) !== false) {
            $talks[] = $this->parseFromStdClass($result);
        }

        $this->database->free($statement);

        return $talks;
    }

    function deletePendingTalksByTalkSeries(\ilObjEmployeeTalkSeries $series): void {
        $subItems = $subItems = $series->getSubItems()['_all'];
        $statement = $this->database->prepare('SELECT * FROM etal_data WHERE employee=?;', ["integer"]);
        $statement = $statement->execute([$iliasUserId]);
    }

    private function parseFromStdClass($stdClass): EmployeeTalk {
        return new EmployeeTalk(
            intval($stdClass->object_id),
            new ilDateTime($stdClass->start_date, IL_CAL_UNIX, ilTimeZone::UTC),
            new ilDateTime($stdClass->end_date, IL_CAL_UNIX, ilTimeZone::UTC),
            boolval($stdClass->all_day),
            $stdClass->series_id ?? '',
            $stdClass->location ?? '',
            intval($stdClass->employee),
            boolval($stdClass->completed),
            boolval($stdClass->standalone_date)
        );
    }
}