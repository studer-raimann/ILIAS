<?php

namespace ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Command;

use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\QuestionRepository;
<<<<<<< HEAD
use ILIAS\AssessmentQuestion\Common\DomainModel\Aggregate\Command\CommandContract;
use ILIAS\AssessmentQuestion\Common\DomainModel\Aggregate\Command\CommandHandlerContract;
=======
use ILIAS\Messaging\Contract\Command\Command;
use ILIAS\Messaging\Contract\Command\CommandHandler;
>>>>>>> feature/6-0/AssessmentQuestion_WIP_al

/**
 * Class CreateQuestionHandler
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Command
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
<<<<<<< HEAD
class SaveQuestionCommandHandler implements CommandHandlerContract {
=======
class SaveQuestionCommandHandler implements CommandHandler {
>>>>>>> feature/6-0/AssessmentQuestion_WIP_al

	/**
	 * @param SaveQuestionCommand $command
	 */
<<<<<<< HEAD
	public function handle(CommandContract $command) {
=======
	public function handle(Command $command) {
>>>>>>> feature/6-0/AssessmentQuestion_WIP_al
		QuestionRepository::getInstance()->save($command->GetQuestion());
	}
}