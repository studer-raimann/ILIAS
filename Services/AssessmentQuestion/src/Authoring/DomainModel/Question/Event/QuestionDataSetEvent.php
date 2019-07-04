<?php

namespace ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Event;

use ILIAS\AssessmentQuestion\Common\DomainModel\Aggregate\DomainObjectId;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Shared\AbstractDomainObjectId;
use ILIAS\AssessmentQuestion\Common\DomainModel\Aggregate\Event\AbstractDomainEvent;
use QuestionData;

/**
 * Class QuestionCreatedEvent
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Event
 * @author  Martin Studer <ms@studer-raimann.ch>
 */
class QuestionDataSetEvent extends AbstractDomainEvent {

	public const NAME = 'QuestionDataSetEvent';
	/**
	 * @var QuestionData
	 */
	public $data;

	public function __construct(DomainObjectId $id, int $creator_id, QuestionData $data = null)
	{
		parent::__construct($id, $creator_id);
		$this->data = $data;
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
	 * @return string
	 */
	public function getData(): string {
		return $this->data;
	}

	public function getEventBody(): string {
		return json_encode($this->data);
	}


	/**
	 * @param string $json_data
	 */
	public function restoreEventBody(string $json_data) {
		$data = json_decode($json_data);
		$this->data = new QuestionData($data->title,
		                               $data->description,
		                               $data->question_text);
	}
}