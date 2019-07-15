<?php

namespace ILIAS\AssessmentQuestion\Authoring\DomainModel\Question;

use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option\AnswerOptions;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Event\QuestionAnswerOptionsSetEvent;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Event\QuestionPlayConfigurationSetEvent;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Event\QuestionRevisionCreatedEvent;
use ILIAS\AssessmentQuestion\Common\DomainModel\Aggregate\DomainObjectId;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Event\QuestionDataSetEvent;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Event\QuestionCreatedEvent;
use ILIAS\AssessmentQuestion\Common\DomainModel\Aggregate\AggregateRoot;
use ILIAS\AssessmentQuestion\Common\DomainModel\Aggregate\Event\DomainEvents;
use ILIAS\AssessmentQuestion\Common\IsRevisable;
use ILIAS\AssessmentQuestion\Common\RevisionId;
use ILIAS\AssessmentQuestion\Common\DomainModel\Aggregate\AbstractEventSourcedAggregateRoot;

/**
 * Class Question
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question
 * @author  Martin Studer <ms@studer-raimann.ch>
 */
class Question extends AbstractEventSourcedAggregateRoot implements IsRevisable {

	//TODO get that from DB
	const SYSTEM_USER_ID = 3;

	/**
	 * @var DomainObjectId
	 */
	private $id;
	/**
	 * @var RevisionId
	 */
	private $revision_id;
	/**
	 * @var string
	 */
	private $revision_name;
	/**
	 * @var int
	 */
	private $creator_id;
	/**
	 * @var QuestionData
	 */
	private $data;
	/**
	 * @var QuestionPlayConfiguration
	 */
	private $play_configuration;
	/**
	 * @var AnswerOptions
	 */
	private $answer_options;

	protected function __construct() {
		$this->answer_options = new AnswerOptions();
		parent::__construct();
	}


	/**
	 * @param string $title
	 * @param string $description
	 *
	 * @param int    $creator_id
	 *
	 * @return Question
	 */
	public static function createNewQuestion(int $creator_id) {
		$question = new Question();
		$question->ExecuteEvent(new QuestionCreatedEvent(new DomainObjectId(), $creator_id));
		return $question;
	}

	protected function applyQuestionCreatedEvent(QuestionCreatedEvent $event) {
		$this->id = $event->getAggregateId();
		$this->creator_id = $event->getInitiatingUserId();
	}

	protected function applyQuestionDataSetEvent(QuestionDataSetEvent $event) {
		$this->data = $event->getData();
	}

	protected function applyQuestionPlayConfigurationSetEvent(QuestionPlayConfigurationSetEvent $event) {
		$this->play_configuration = $event->getPlayConfiguration();
	}

	protected function applyQuestionRevisionCreatedEvent(QuestionRevisionCreatedEvent $event) {
		$this->revision_id = new RevisionId($event->getRevisionKey());
	}

	protected function applyQuestionAnswerOptionsSetEvent(QuestionAnswerOptionsSetEvent $event) {
		$this->answer_options = $event->getAnswerOptions();
	}

	public function getOnlineState() : bool {
		return $this->online;
	}

	/**
	 * @return QuestionData
	 */
	public function getData(): QuestionData {
		return $this->data;
	}

	/**
	 * @param QuestionData $data
	 * @param int          $creator_id
	 */
	public function setData(QuestionData $data, int $creator_id = self::SYSTEM_USER_ID): void {
		$this->ExecuteEvent(new QuestionDataSetEvent($this->getAggregateId(), $creator_id, $data));
	}

	/**
	 * @return QuestionPlayConfiguration
	 */
	public function getPlayConfiguration(): ?QuestionPlayConfiguration {
		return $this->play_configuration;
	}


	/**
	 * @param QuestionPlayConfiguration $play_configuration
	 */
	public function setPlayConfiguration(QuestionPlayConfiguration $play_configuration, int $creator_id = self::SYSTEM_USER_ID): void {
		$this->ExecuteEvent(new QuestionPlayConfigurationSetEvent($this->getAggregateId(), $creator_id, $play_configuration));
	}

	/**
	 * @return AnswerOptions
	 */
	public function getAnswerOptions(): AnswerOptions {
		return $this->answer_options;
	}

	public function setAnswerOptions(AnswerOptions $options, int $creator_id = self::SYSTEM_USER_ID) {
		$this->ExecuteEvent(new QuestionAnswerOptionsSetEvent($this->getAggregateId(), $creator_id, $options));
	}

	/**
	 * @return int
	 */
	public function getCreatorId(): int {
		return $this->creator_id;
	}


	/**
	 * @param int $creator_id
	 */
	public function setCreatorId(int $creator_id): void {
		$this->creator_id = $creator_id;
	}


	/**
	 * @return RevisionId revision id of object
	 */
	public function getRevisionId(): ?RevisionId {
		return $this->revision_id;
	}


	/**
	 * @param RevisionId $id
	 *
	 * Revision id is only to be set by the RevisionFactory when generating a
	 * revision or by the persistance layer when loading an object
	 *
	 * @return mixed
	 */
	public function setRevisionId(RevisionId $id) {
		$this->ExecuteEvent(new QuestionRevisionCreatedEvent($this->getAggregateId(), $this->creator_id, $id->GetKey()));
	}

	/**
	 * @return string
	 *
	 * Name of the revision used by the RevisionFactory when generating a revision
	 * Using of Creation Date and or an increasing Number are encouraged
	 *
	 */
	public function getRevisionName(): ?string {
		return time();
	}


	/**
	 * @return array
	 *
	 * Data used for signing the revision, so this method needs to to collect all
	 * Domain specific data of an object and return it as an array
	 */
	public function getRevisionData(): array {
		$data[] = $this->getAggregateId()->getId();
		$data[] = $this->getData()->jsonSerialize();
		return $data;
	}


	public static function reconstitute(DomainEvents $event_history): AggregateRoot {
		$question = new Question();
		foreach ($event_history->getEvents() as $event) {
			$question->applyEvent($event);
		}
		return $question;
	}


	function getAggregateId(): DomainObjectId {
		return $this->id;
	}
}