<?php

namespace ILIAS\AssessmentQuestion\Authoring\DomainModel\Question;

use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Event\GenericEvent;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Shared\QuestionId;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Event\QuestionCreatedEvent;
use ILIAS\Data\Domain\Entity\AggregateId;
use ILIAS\Data\Domain\Entity\EntitiyId;
use ILIAS\Data\Domain\Entity\IsRevisable;
use ILIAS\Data\Domain\Entity\AggregateRoot;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Event\EventStream;
use ILIAS\Data\Domain\Entity\RevisionId;
use ILIAS\Data\Domain\Event\DomainEvent;
use ILIAS\Data\Domain\Event\DomainEvents;
use SAML2\Configuration\EntityIdProvider;

/**
 * Class Question
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question
 * @author  Martin Studer <ms@studer-raimann.ch>
 */
class Question extends AggregateRoot implements IsRevisable {
	/**
	 * @var QuestionId
	 */
	private $id;
	/**
	 * @var RevisionId
	 */
	private $revision_id;
	/**
	 * @var string
	 */
	private $revision_name = "";
	/**
	 * @var string
	 */
	private $title;
	/**
	 * @var string
	 */
	private $description;
	/**
	 * @var int
	 */
	private $creator;
	/**
	 * @var bool
	 */
	private $online = false;
	/**
	 * @var
	 */
	private $possible_answers;


	protected function __construct() {
		parent::__construct();
	}

	public static function reconstitute(DomainEvents $history) {
		$question = new Question();

		foreach ($history->getEvents() as $event) {
			$question->applyEvent($event);;
		}

		return $question;
	}


	/**
	 * @param string $title
	 * @param string $description
	 *
	 * @param int    $creator
	 *
	 * @return Question
	 */
	public static function createNewQuestion(string $title, string $description, int $creator) {
		$question = new Question();
		$question->ExecuteEvent(new QuestionCreatedEvent(new QuestionId(), $creator, $title, $description));
		return $question;
	}

	protected function applyQuestionCreatedEvent(QuestionCreatedEvent $event) {
		$this->id = $event->getAggregateId();
		$this->creator = $event->getInitiatingUserId();
		$this->description = $event->getDescription();
		$this->title = $event->getTitle();
	}


	public function setOnline() {
		$this->ExecuteEvent(new QuestionStatusHasChangedToOnlineEvent($this->id));
	}


	protected function applyQuestionStatusHasChangedToOnline(QuestionStatusHasChangedToOnlineEvent $event) {
		$this->online = true;
	}


	public function setOffline() {
		$this->ExecuteEvent(new QuestionStatusHasChangedToOfflineEvent($this->id));
	}


	protected function applyQuestionStatusHasChangedToOffline(QuestionStatusHasChangedToOfflineEvent $event) {
		$this->online = false;
	}

	// TODO intressiert mich revision hier wirklich, schlussendlich projektion mit id -> wenn bereits projektion mit id dann revision += 1 sonst revision = 1 change revision würde implizieren das ich nach revision 4 plötzlich revision 2 erstellen möchte
	public function createRevision($revision) {
		$this->ExecuteEvent(new RevisionWasCreated($this->id, $revision));
	}


	protected function applyRevisionWasCreated(RevisionWasCreated $event) {
		//TODO implement me
	}


	public function changeSettingsFor($settings) {
		$this->ExecuteEvent(new QuestionSettingsWereChanged($this->id, $settings));
	}


	protected function applyQuestionSettingsWereChanged(QuestionSettingsWereChanged $event) {
		$this->settings = $event->settings();
	}

	function getAggregateId(): AggregateId {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}


	/**
	 * @param string $title
	 */
	public function setTitle(string $title): void {
		$this->title = $title;
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}


	/**
	 * @param string $description
	 */
	public function setDescription(string $description): void {
		$this->description = $description;
	}

	/**
	 * @return int
	 */
	public function getCreator(): int {
		return $this->creator;
	}


	/**
	 * @param int $creator
	 */
	public function setCreator(int $creator): void {
		$this->creator = $creator;
	}

	/**
	 * @return RevisionId revision id of object
	 */
	public function getRevisionId(): RevisionId {
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
		$this->revision_id = $id;
	}

	/**
	 * @return string
	 *
	 * Name of the revision used by the RevisionFactory when generating a revision
	 * Using of Creation Date and or an increasing Number are encouraged
	 *
	 */
	public function getRevisionName(): string {
		return $this->revision_name;
	}

	/**
	 * @return array
	 *
	 * Data used for signing the revision, so this method needs to to collect all
	 * Domain specific data of an object and return it as an array
	 */
	public function getRevisionData(): array {
		$data['title'] = $this->getTitle();
		$data['description'] = $this->getDescription();
		$data['creator'] = $this->getCreator();
	}
}