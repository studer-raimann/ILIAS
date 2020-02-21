<?php

namespace ILIAS\AssessmentQuestion\DomainModel\Command;

use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\QuestionLegacyData;
use ILIAS\AssessmentQuestion\DomainModel\QuestionRepository;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;

/**
 * Class CreateQuestionCommandHandler
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Command
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class CreateQuestionCommandHandler implements CommandHandlerContract {

    /**
     * @param CommandContract $command
     */
	public function handle(CommandContract $command) {
        /** @var CreateQuestionCommand $command */
	    
        /** @var Question $question */
		$question = Question::createNewQuestion(
			$command->getQuestionUuid(),
            $command->getQuestionContainerId(),
			$command->getIssuingUserId(),
		    QuestionRepository::getInstance()->getNextId()
		);

		$question->setLegacyData(
			QuestionLegacyData::create(
				$command->getQuestionType(),
			    $command->getContentEditingMode()
			),
		    $command->getQuestionContainerId(),
		    $command->getIssuingUserId()
		);

		QuestionRepository::getInstance()->save($question);
	}
}