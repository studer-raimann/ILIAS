<?php
declare(strict_types=1);

namespace ILIAS\EmployeeTalk\Service;

use ilObjEmployeeTalk;
use ilObjUser;

final class VEventFactory
{
    /**
     * @param ilObjEmployeeTalk $talk
     * @param string            $status VEventStatus
     * @return VEvent
     *
     * @see VEventStatus
     */
    public static function getInstanceFromTalk(ilObjEmployeeTalk $talk, string $status = VEventStatus::CONFIRMED): VEvent {
        $data = $talk->getData();
        $superior = new ilObjUser($talk->getOwner());
        $employee = new ilObjUser($talk->getData()->getEmployee());
        $superiorName = $superior->getFullname();

        return new VEvent(
            md5($talk->getType() . $talk->getId()),
            $talk->getTitle(),
            $talk->getTitle(),
            0,
            $status,
            $superiorName,
            $superior->getEmail(),
            $employee->getFullname(),
            $employee->getEmail(),
            $data->getStartDate()->getUnixTime(),
            $data->getEndDate()->getUnixTime(),
            $data->isAllDay(),
            '',
            $data->getLocation()
        );
    }
}