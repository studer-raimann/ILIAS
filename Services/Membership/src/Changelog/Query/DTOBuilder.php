<?php

namespace ILIAS\Membership\Changelog\Query;

use ILIAS\Membership\Changelog\Infrastructure\AR\EventAR;

/**
 * Class DTOBuilder
 *
 * @package ILIAS\Membership\Changelog\Query
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class DTOBuilder
{

    use SingletonTrait;


    /**
     * @param EventAR[] $EventARs
     *
     * @return EventDTO[]
     */
    public function buildEventDTOsFromARs(array $EventARs) : array
    {
        $return = [];
        foreach ($EventARs as $EventAR) {
            $return[$EventAR->getEventId()->getId()] = $this->buildEventDTOFromAR($EventAR);
        }

        return $return;
    }


    /**
     * @param EventAR $EventAR
     *
     * @return EventDTO
     */
    public function buildEventDTOFromAR(EventAR $EventAR) : EventDTO
    {
        return new EventDTO(
            $EventAR->getId(),
            $EventAR->getEventId()->getId(),
            $EventAR->getEventName(),
            $EventAR->getActorUserId(),
            $EventAR->getActorUserId() ? $EventAR->getUsrDataLastname() . ', ' . $EventAR->getUsrDataFirstname() : '',
            $EventAR->getSubjectUserId(),
            $EventAR->getSubjectUserId() ? $EventAR->usr_data_2_lastname . ', ' . $EventAR->usr_data_2_firstname : '',
            $EventAR->getSubjectObjId(),
            $EventAR->getObjectDataTitle() ?: '',
            $EventAR->getILIASComponent(),
            $EventAR->getAdditionalData(),
            $EventAR->getTimestamp()
        );
    }
}