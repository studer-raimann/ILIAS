<?php

namespace ILIAS\AssessmentQuestion\DomainModel\Event;


use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Event\AbstractIlContainerItemDomainEvent;

/**
 * Class QuestionRevisionCreatedEvent
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class QuestionRevisionCreatedEvent extends AbstractIlContainerItemDomainEvent {
	public const NAME = 'QuestionRevisionCreatedEvent';
	/**
	 * @var string
	 */
	public $revision_key;


    /**
     * QuestionRevisionCreatedEvent constructor.
     *
     * @param DomainObjectId $id
     * @param int            $container_obj_id
     * @param int            $initating_user_id
     * @param string         $revision_key
     */
	public function __construct(DomainObjectId $aggregate_id, 
	                            int $container_obj_id, 
	                            int $initiating_user_id, 
	                            int $question_int_id, 
	                            string $revision_key = "")
	{
	    parent::__construct($aggregate_id, $question_int_id, $container_obj_id, $initiating_user_id);
	    
		$this->revision_key = $revision_key;
	}

	/**
	 * @return string
	 *
	 * Add a Constant EVENT_NAME to your class: Name it: [aggregate].[event]
	 * e.g. 'question.created'
	 */
	public function getEventName(): string {
		return self::NAME;
	}

    /**
     * @param string $json_data
     */
	public function restoreEventBody(string $json_data) : void {
		$this->revision_key = $json_data;
	}

	/**
	 * @return string
	 */
	public function getRevisionKey(): string {
		return $this->revision_key;
	}
    public function getEventBody(): string
    {
        return $this->revision_key;
    }
}