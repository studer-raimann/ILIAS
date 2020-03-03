<?php

namespace ILIAS\AssessmentQuestion\DomainModel\Event;



use ILIAS\AssessmentQuestion\DomainModel\Answer\Option\AnswerOptions;
use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Event\AbstractIlContainerItemDomainEvent;

/**
 * Class QuestionAnswerOptionsSetEvent
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class QuestionAnswerOptionsSetEvent extends AbstractIlContainerItemDomainEvent {

	public const NAME = 'QuestionAnswerOptionsSetEvent';
	/**
	 * @var AnswerOptions
	 */
	protected $answer_options;


    /**
     * QuestionAnswerOptionsSetEvent constructor.
     *
     * @param DomainObjectId     $id
     * @param int                $container_obj_id
     * @param int                $initiating_user_id
     * @param AnswerOptions|null $options
     *
     * @throws \ilDateTimeException
     */
	public function __construct(DomainObjectId $aggregate_id, 
	                            int $container_obj_id, 
	                            int $initiating_user_id, 
	                            int $question_int_id, 
	                            AnswerOptions $options = null)
	{
	    parent::__construct($aggregate_id, $question_int_id, $container_obj_id, $initiating_user_id);
	    
		$this->answer_options = $options;
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
	 * @return AnswerOptions
	 */
	public function getAnswerOptions(): AnswerOptions {
		return $this->answer_options;
	}

	public function getEventBody(): string {
		return json_encode($this->answer_options->getOptions());
	}

	/**
	 * @param string $json_data
	 */
	public function restoreEventBody(string $json_data) : void {
        $this->answer_options = AnswerOptions::deserialize($json_data);
	}
}