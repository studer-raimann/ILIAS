<?php
namespace ILIAS\AssessmentQuestion\Authoring\UserInterface\Web\Form\Command;


use ILIAS\AssessmentQuestion\Authoring\Application\AuthoringApplicationService;
use ILIAS\AssessmentQuestion\Authoring\Application\AuthoringApplicationServiceSpec;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Type\AnswerType;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\QuestionContainer;
use ILIAS\AssessmentQuestion\Authoring\UserInterface\Web\Form\Form\CreateQuestionForm;
use ILIAS\AssessmentQuestion\Authoring\UserInterface\Web\Form\Input\questionTypeSelect;
use ILIAS\AssessmentQuestion\Common\DomainModel\Aggregate\Command\CommandContract;
use ILIAS\AssessmentQuestion\Common\DomainModel\Aggregate\Command\CommandHandlerContract;
use ILIAS\AssessmentQuestion\Common\DomainModel\Aggregate\DomainObjectId;

class saveCreateQuestionFormCommandHandler implements CommandHandlerContract {

	public function handle(CommandContract $command) {
		global $DIC;
		/**
		 * @var saveCreateQuestionFormCommand $command
		 */
		$cmd = $command;

		$form = new CreateQuestionForm();
		$result = $form->getForm($cmd->getCreateQuestionFormSpec())->withRequest($cmd->getRequest())->getData();

		$uuid = new \ILIAS\Data\UUID\Factory();
		$question_uuid = new DomainObjectId($uuid->uuid4()->toString());

		$authoring_service_spec = new AuthoringApplicationServiceSpec(
			$question_uuid,
			71);
		$authoring_service = new AuthoringApplicationService($authoring_service_spec);
		$authoring_service->CreateQuestion(new QuestionContainer(67),
			new AnswerType($result[0]['question_type']));

		//TODO
		$arr_classes = [];
		$cmd_class = '';
		foreach($DIC->ctrl()->getCallHistory() as $arr) {
			$arr_classes[] = $arr['class'];
			$cmd_class  = $arr['class'];
		}

		$DIC->ctrl()->setParameterByClass($cmd_class,'question_uuid',$question_uuid->getId());
		$DIC->ctrl()->redirectByClass($arr_classes,showLegacyQuestionFormCommand::getName());
	}
}