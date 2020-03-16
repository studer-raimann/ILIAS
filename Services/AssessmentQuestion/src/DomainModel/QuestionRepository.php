<?php

namespace ILIAS\AssessmentQuestion\DomainModel;



use ILIAS\AssessmentQuestion\Infrastructure\Persistence\EventStore\QuestionEventStoreRepository;
use srag\CQRS\Aggregate\AbstractEventSourcedAggregateRepository;
use srag\CQRS\Aggregate\AggregateRoot;
use srag\CQRS\Event\DomainEvents;
use srag\CQRS\Event\EventStore;

/**
 * Class QuestionRepository
 *
 * @package ILIAS\AssessmentQuestion\Authoring\Infrastructure\Persistence
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class QuestionRepository extends AbstractEventSourcedAggregateRepository {

	/**
	 * @var QuestionEventStoreRepository
	 */
	private $event_store;

    /**
     * QuestionRepository constructor.
     */
	protected function __construct() {
		parent::__construct();
		$this->event_store = new QuestionEventStoreRepository();
	}

	/**
	 * @return EventStore
	 */
	protected function getEventStore(): EventStore {
		return $this->event_store;
	}

    /**
     * @param DomainEvents $event_history
     *
     * @return AggregateRoot
     */
	protected function reconstituteAggregate(DomainEvents $event_history): AggregateRoot {
		return Question::reconstitute($event_history);
	}
	
	/**
	 * @return int
	 */
	public function getNextId() : int {
	    return $this->event_store->getNextId();
	}
	
	public function getAggregateByIliasId(int $id) : Question {
	    $aggregate_id = $this->event_store->getAggregateIdOfIliasId($id);
	    
	    return $this->getAggregateRootById($aggregate_id);
	}
}
