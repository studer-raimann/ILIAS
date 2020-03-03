<?php

namespace ILIAS\AssessmentQuestion\DomainModel\Event;

use ILIAS\AssessmentQuestion\DomainModel\QuestionData;
use ILIAS\Services\AssessmentQuestion\DomainModel\Feedback;
use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Event\AbstractIlContainerItemDomainEvent;

/**
 * Class QuestionFeedbackSetEvent
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class QuestionFeedbackSetEvent extends AbstractIlContainerItemDomainEvent {

	public const NAME = 'QuestionFeedbackSetEvent';
	/**
	 * @var Feedback
	 */
	protected $feedback;


    /**
     * QuestionDataSetEvent constructor.
     *
     * @param DomainObjectId    $id
     * @param int               $creator_id
     * @param QuestionData|null $data
     *
     * @throws \ilDateTimeException
     */
	public function __construct(DomainObjectId $aggregate_id, 
	                            int $container_obj_id, 
	                            int $initiating_user_id, 
	                            int $question_int_id,
                                Feedback $feedback = null)
	{
	    parent::__construct($aggregate_id, $question_int_id, $container_obj_id, $initiating_user_id);
	    
		$this->feedback = $feedback;
	}

	/**
	 * @return string
	 *
	 * Add a Constant EVENT_NAME to your class: Name it: Classname
	 * e.g. 'QuestionCreatedEvent'
	 */
	public function getEventName(): string {
		return self::NAME;
	}

	/**
	 * @return Feedback
	 */
	public function getFeedback(): Feedback {
		return $this->feedback;
	}

    /**
     * @return string
     */
	public function getEventBody(): string {
		return json_encode($this->feedback);
	}

	/**
	 * @param string $json_data
	 */
	public function restoreEventBody(string $json_data) : void {
		$this->feedback = Feedback::deserialize($json_data);
	}
}