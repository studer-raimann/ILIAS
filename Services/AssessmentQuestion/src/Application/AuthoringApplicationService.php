<?php

namespace ILIS\AssessmentQuestion\Application;

use ILIAS\AssessmentQuestion\CQRS\Aggregate\AbstractValueObject;
use ILIAS\AssessmentQuestion\CQRS\Aggregate\DomainObjectId;
use ILIAS\AssessmentQuestion\CQRS\Command\CommandBusBuilder;
use ILIAS\AssessmentQuestion\DomainModel\Command\CreateQuestionCommand;
use ILIAS\AssessmentQuestion\DomainModel\Command\CreateQuestionRevisionCommand;
use ILIAS\AssessmentQuestion\DomainModel\Command\SaveQuestionCommand;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\DomainModel\QuestionRepository;
use ILIAS\AssessmentQuestion\Infrastructure\Persistence\EventStore\QuestionEventStoreRepository;

const MSG_SUCCESS = "success";

/**
 * Class AuthoringApplicationService
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class AuthoringApplicationService {

    /**
     * @var int
     */
    protected $container_obj_id;

	/**
	 * @var int
	 */
	protected $actor_user_id;

	/**
	 * AsqAuthoringService constructor.
     *
     * @param int $container_obj_id
	 * @param int $actor_user_id
	 */
	public function __construct(int $container_obj_id, int $actor_user_id) {
	    $this->container_obj_id = $container_obj_id;
		$this->actor_user_id = $actor_user_id;
	}


	/**
	 * @param string $aggregate_id
	 *
	 * @return QuestionDto
	 */
	public function GetQuestion(string $aggregate_id) : QuestionDto {
		$question = QuestionRepository::getInstance()->getAggregateRootById(new DomainObjectId($aggregate_id));
		return QuestionDto::CreateFromQuestion($question);
	}

	public function CreateQuestion(
		DomainObjectId $question_uuid,
		int $container_id,
		?int $answer_type_id = null
	): void {
		//CreateQuestion.png
		CommandBusBuilder::getCommandBus()->handle
		(new CreateQuestionCommand
		 ($question_uuid,
		  $this->actor_user_id,
		  $container_id,
		  $answer_type_id));
	}

	public function SaveQuestion(QuestionDto $question_dto) {
		// check changes and trigger them on question if there are any
		/** @var Question $question */
		$question = QuestionRepository::getInstance()->getAggregateRootById(new DomainObjectId($question_dto->getId()));

		if (!AbstractValueObject::isNullableEqual($question_dto->getData(), $question->getData())) {
			$question->setData($question_dto->getData(), $this->container_obj_id, $this->actor_user_id);
		}

		if (!AbstractValueObject::isNullableEqual($question_dto->getPlayConfiguration(), $question->getPlayConfiguration())) {
			$question->setPlayConfiguration($question_dto->getPlayConfiguration(), $this->container_obj_id, $this->actor_user_id);
		}

		// TODO implement equals for answer options
		if ($question_dto->getAnswerOptions() !== $question->getAnswerOptions()){
			$question->setAnswerOptions($question_dto->getAnswerOptions(), $this->container_obj_id, $this->actor_user_id);
		}

		if(count($question->getRecordedEvents()->getEvents()) > 0) {
			// save changes if there are any
			CommandBusBuilder::getCommandBus()->handle(new SaveQuestionCommand($question, $this->actor_user_id));
		}
	}

	public function projectQuestion(string $question_id) {
	    CommandBusBuilder::getCommandBus()->handle(new CreateQuestionRevisionCommand($question_id, $this->actor_user_id));
	}

	public function DeleteQuestion(string $question_id) {
		// deletes question
		// no image
	}

	/* Ich würde die Answers immer als Ganzes behandeln
	public function RemoveAnswerFromQuestion(string $question_id, $answer) {
		// remove answer from question
	}*/

    /**
     * @return QuestionDto[]
     */
	public function GetQuestions():array {
	    $questions = [];
		$event_store = new QuestionEventStoreRepository();
	    foreach($event_store->allStoredQuestionIdsForContainerObjId($this->container_obj_id) as $aggregate_id) {
            $questions[] = $this->GetQuestion($aggregate_id);
        }
	    return $questions;
	}
}