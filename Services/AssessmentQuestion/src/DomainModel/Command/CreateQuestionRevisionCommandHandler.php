<?php

namespace ILIAS\AssessmentQuestion\DomainModel\Command;

use ILIAS\AssessmentQuestion\DomainModel\QuestionRepository;
use ILIAS\AssessmentQuestion\DomainModel\Projection\ProjectQuestions;
use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Aggregate\RevisionFactory;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;

/**
 * Class CreateQuestionRevisionCommandHandler
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class CreateQuestionRevisionCommandHandler implements CommandHandlerContract {

    /**
     * @param CommandContract $command
     */
	public function handle(CommandContract $command) {
	    /** @var CreateQuestionRevisionCommand $command */
		$question = QuestionRepository::getInstance()->getAggregateRootById(new DomainObjectId($command->getQuestionId()));
		RevisionFactory::setRevisionId($question);
		$projector = new ProjectQuestions();
		$projector->project($question);
		QuestionRepository::getInstance()->save($question);
	}
}