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

    function findByObjectId(int $objectId) : EmployeeTalk
    {
        $statement = $this->database->prepare('SELECT * FROM etal_data WHERE object_id=?;', ["integer"]);
        $statement = $statement->execute([$objectId]);
        $result = $statement->fetchObject();
        $this->database->free($statement);

        //TODO raise exception if result count is 0 or greater 1

        return new EmployeeTalk(
            intval($result->object_id),
            new ilDateTime($result->start_date, IL_CAL_UNIX, ilTimeZone::UTC),
            new ilDateTime($result->end_date, IL_CAL_UNIX, ilTimeZone::UTC),
            boolval($result->all_day),
            $result->series_id ?? '',
            $result->location ?? '',
            intval($result->employee),
            boolval($result->completed)
        );
    }

    function create(EmployeeTalk $talk) : EmployeeTalk
    {
        $this->database->insert('etal_data', [
            'object_id'     => ['int', $talk->getObjectId()],
            'series_id'     => ['text', $talk->getSeriesId()],
            'start_date'    => ['int', $talk->getStartDate()->getUnixTime()],
            'end_date'      => ['int', $talk->getEndDate()->getUnixTime()],
            'all_day'       => ['int', $talk->isAllDay()],
            'location'      => ['text', $talk->getLocation()],
            'employee'      => ['int', $talk->getEmployee()],
            'completed'     => ['int', $talk->isCompleted()]
            ]);

        return $talk;
    }

    function update(EmployeeTalk $talk) : EmployeeTalk
    {
        $this->database->update('etal_data', [
            'series_id'     => ['text', $talk->getSeriesId()],
            'start_date'    => ['int', $talk->getStartDate()->getUnixTime()],
            'end_date'      => ['int', $talk->getEndDate()->getUnixTime()],
            'all_day'       => ['int', $talk->isAllDay()],
            'location'      => ['text', $talk->getLocation()],
            'employee'      => ['int', $talk->getEmployee()],
            'completed'     => ['int', $talk->isCompleted()]
        ], [
            'object_id'     => ['int', $talk->getObjectId()]
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
        while (($result = $statement->fetchObject()) !== null) {
            $talks[] = new EmployeeTalk(
                intval($result->object_id),
                new ilDateTime($result->start_date, IL_CAL_UNIX, ilTimeZone::UTC),
                new ilDateTime($result->end_date, IL_CAL_UNIX, ilTimeZone::UTC),
                boolval($result->all_day),
                $result->series_id,
                $result->location ?? '',
                $result->employee,
                boolval($result->completed)
            );
        }

        $this->database->free($statement);

        return $talks;
    }

    function findBySeries(string $seriesId) : array
    {
        $statement = $this->database->prepare('SELECT * FROM etal_data WHERE series_id=?;', ["text"]);
        $statement = $statement->execute([$seriesId]);

        $talks = [];
        while (($result = $statement->fetchObject()) !== null) {
            $talks[] = new EmployeeTalk(
                intval($result->object_id),
                new ilDateTime($result->start_date, IL_CAL_UNIX, ilTimeZone::UTC),
                new ilDateTime($result->end_date, IL_CAL_UNIX, ilTimeZone::UTC),
                boolval($result->all_day),
                $result->series_id,
                $result->location ?? '',
                $result->employee,
                boolval($result->completed)
            );
        }

        $this->database->free($statement);

        return $talks;
    }
}