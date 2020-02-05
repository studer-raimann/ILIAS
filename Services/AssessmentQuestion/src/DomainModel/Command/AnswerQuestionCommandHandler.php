<?php

namespace ILIAS\AssessmentQuestion\DomainModel\Command;


use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\QuestionRepository;
use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;

/**
 * Class AnswerQuestionCommandHandler
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class AnswerQuestionCommandHandler implements CommandHandlerContract {

    /**
     * @param CommandContract $command
     */
	public function handle(CommandContract $command) {
	    /** @var AnswerQuestionCommand $command $question_id */
	    $question_id = $command->getAnswer()->getQuestionId();
	    
	    /** @var AnswerQuestionCommand $command */
		$repo = QuestionRepository::getInstance();
		/** @var Question $question */
		$question = $repo->getAggregateRootById(new DomainObjectId($question_id));
		$question->addAnswer($command->getAnswer(), $question->getContainerObjId($question_id));
		QuestionRepository::getInstance()->save($question);
	}
}