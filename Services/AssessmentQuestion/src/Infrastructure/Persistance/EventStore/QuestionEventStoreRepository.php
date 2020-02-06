<?php

namespace ILIAS\AssessmentQuestion\Infrastructure\Persistence\EventStore;

use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Event\AbstractDomainEvent;
use srag\CQRS\Event\AbstractIlContainerItemDomainEvent;
use srag\CQRS\Event\DomainEvents;
use srag\CQRS\Event\EventID;
use srag\CQRS\Event\EventStore;
use srag\CQRS\Event\AbstractIlContainerDomainEvent;
use ILIAS\UI\NotImplementedException;


/**
 * Class QuestionEventStoreRepository
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class QuestionEventStoreRepository implements EventStore {

	//TODO Constructor with DIC->DB-Connection - we will be a microservice

	/**
	 * @param DomainEvents $events
	 *
	 * @return void
	 */
	public function commit(DomainEvents $events) : void {
		/** @var AbstractIlContainerItemDomainEvent $event */
		foreach ($events->getEvents() as $event) {
			$stored_event = new QuestionEventStoreAr();
			
			$stored_event->setCIEventData(
			    new EventID(), 
			    $event->getAggregateId(), 
			    $event->getItemId(), 
			    $event->getContainerId(), 
			    $event->getEventName(), 
			    $event->getOccurredOn(), 
			    $event->getInitiatingUserId(), 
			    $event->getEventBody(), 
			    get_class($event)
			);

			$stored_event->create();
		}
	}


	/**
	 * @param DomainObjectId $id
	 *
	 * @return DomainEvents
	 */
	public function getAggregateHistoryFor(DomainObjectId $id): DomainEvents {
		global $DIC;

		$sql = "SELECT * FROM " . QuestionEventStoreAr::STORAGE_NAME . " where aggregate_id = " . $DIC->database()->quote($id->getId(),'string');
		$res = $DIC->database()->query($sql);

		$event_stream = new DomainEvents();
		while ($row = $DIC->database()->fetchAssoc($res)) {
			/**@var AbstractDomainEvent $event */
			$event_name = "ILIAS\\AssessmentQuestion\\DomainModel\\Event\\".utf8_encode(trim($row['event_name']));
			$event = new $event_name(new DomainObjectId($row['aggregate_id']), $row['container_id'], $row['initiating_user_id'], $row['item_id']);
			$event->restoreEventBody($row['event_body']);
			$event_stream->addEvent($event);
		}

		return $event_stream;
	}


    /**
     * @param int $container_obj_id
     *
     * @return array
     */
	public function allStoredQuestionIdsForContainerObjId(int $container_obj_id): array {
	   global $DIC;

	   // TODO join with not in select QuestionDeletedEvent
	   $sql = "SELECT aggregate_id FROM " . QuestionEventStoreAr::STORAGE_NAME . " where event_name = 'QuestionCreatedEvent' and container_id = " . $DIC->database()->quote($container_obj_id,'integer');
	   $res = $DIC->database()->query($sql);

	   $arr_data = [];
	   while ($row = $DIC->database()->fetchAssoc($res)) {
	           $arr_data[] = $row['aggregate_id'];
	   }

	   return $arr_data;
	}
	
	/**
	 * @return int
	 */
	public function getNextId() : int {
	    global $DIC;

	    $sql = "SELECT MAX(item_id) as id FROM " . QuestionEventStoreAr::STORAGE_NAME;
	    $res = $DIC->database()->query($sql);
	    $values = $DIC->database()->fetchAssoc($res);
	    return (intval($values['id']) + 1) ?? 1;
	}
	
    public function getEventStream(?EventID $from_position): DomainEvents
    {
        throw new NotImplementedException("Implement evenstream when needed");
    }
}