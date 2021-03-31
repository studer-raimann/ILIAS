<?php
declare(strict_types=1);

namespace ILIAS\Modules\EmployeeTalk\TalkSeries\Repository;

use ilObjEmployeeTalkSeries;
use ilObjUser;
use ilDBInterface;

/**
 * Class IliasDBEmployeeTalkSeriesRepository
 * @package ILIAS\Modules\EmployeeTalk\Talk\Repository
 */
final class IliasDBEmployeeTalkSeriesRepository
{
    /**
     * @var ilObjUser $currentUser
     */
    private $currentUser;
    /**
     * @var ilDBInterface $database
     */
    private $database;

    /**
     * IliasDBEmployeeTalkSeriesRepository constructor.
     * @param ilObjUser     $currentUser
     * @param ilDBInterface $database
     */
    public function __construct(ilObjUser $currentUser, ilDBInterface $database)
    {
        $this->currentUser = $currentUser;
        $this->database = $database;
    }

    /**
     * @return ilObjEmployeeTalkSeries[]
     */
    public function findByOwnerAndEmployee(): array {
        $userId = $this->currentUser->getId();

        //TODO: Alter table talks and store series id, which makes the
        $statement = $this->database->prepare("
            SELECT UNIQUE(od.obj_id) AS objId, oRef.ref_id AS refId
            FROM (
                SELECT tree.parent AS parent, talk.employee AS employee
                FROM etal_data AS talk
                     INNER JOIN object_reference AS oRef ON oRef.obj_id = talk.object_id
                     INNER JOIN tree ON tree.child = oRef.ref_id
                WHERE oRef.deleted IS NULL
                ) AS talk
            INNER JOIN object_reference AS oRef ON oRef.ref_id = talk.parent
            INNER JOIN object_data AS od ON od.obj_id = oRef.obj_id
            WHERE od.type = 'tals' AND (talk.employee = ? OR od.owner = ?);
              ", ["integer", "integer"]);
        $statement = $statement->execute([$userId, $userId]);

        $talkSeries = [];
        while ($result = $statement->fetchObject()) {
            $talkSeries[] = new ilObjEmployeeTalkSeries($result->refId, true);
        }

        $this->database->free($statement);

        return $talkSeries;
    }
}