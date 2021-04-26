<?php
declare(strict_types=1);

namespace ILIAS\EmployeeTalk\Service;

use ilObjEmployeeTalk;
use ilException;
use ilObjEmployeeTalkSeries;

final class VCalendarFactory
{
    /**
     * @param ilObjEmployeeTalk[]  $talks
     * @param string $method
     * @return VCalender
     * @throws ilException
     */
    public static function getInstanceFromTalks(\ilObjEmployeeTalkSeries $series, string $method = VCalenderMethod::PUBLISH): VCalender {
        global $DIC;

        $tree = $DIC->repositoryTree();
        $children = $tree->getChildIds($series->getRefId());
        $talks = array_map(function ($val): ilObjEmployeeTalk {
            return new ilObjEmployeeTalk(intval($val), true);
        }, $children);

        $firstTalk = $talks[0];

        $events = [];

        foreach ($talks as $talk) {
            $events[] = VEventFactory::getInstanceFromTalk($talk);
        }

        return new VCalender(
            $firstTalk->getTitle(),
            md5($series->getType() . $series->getId()),
            $events,
            $method
        );
    }

    /**
     * @param ilObjEmployeeTalkSeries  $series
     * @param string                   $title
     * @param string                   $method
     * @return VCalender
     */
    public static function getEmptyInstance(
        ilObjEmployeeTalkSeries $series,
        string $title,
        string $method = VCalenderMethod::PUBLISH
    ): VCalender {
        return new VCalender(
            $title,
            md5($series->getType() . $series->getId()),
            [],
            $method
        );
    }
}