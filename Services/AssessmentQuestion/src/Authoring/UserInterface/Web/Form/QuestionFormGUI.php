<?php

namespace ILIAS\AssessmentQuestion\Authoring\UserInterface\Web\Form;
use ilHiddenInputGUI;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Question;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\QuestionData;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\QuestionDto;
use ilImageFileInputGUI;
use ilNumberInputGUI;
use \ilPropertyFormGUI;
use \ilTextInputGUI;
use srag\CustomInputGUIs\SrAssessment\MultiLineInputGUI\MultiLineInputGUI;

class QuestionFormGUI extends ilPropertyFormGUI {
	const VAR_TITLE = 'title';
	const VAR_AUTHOR = 'author';
	const VAR_DESCRIPTION = 'description';
	const VAR_QUESTION = 'question';


	/**
	 * QuestionFormGUI constructor.
	 *
	 * @param QuestionDto $question
	 */
	public function __construct($question) {
		$this->initForm($question);

		parent::__construct();
	}


	/**
	 * Init settings property form
	 *
	 * @access private
	 *
	 * @param QuestionDto $question
	 */
	private function initForm($question) {
		$title = new ilTextInputGUI('title', self::VAR_TITLE);
		$title->setRequired(true);
		$title->setValue($question->getData()->getTitle());
		$this->addItem($title);

		$title = new ilTextInputGUI('author', self::VAR_AUTHOR);
		$title->setRequired(true);
		$title->setValue($question->getData()->getAuthor());
		$this->addItem($title);

		$description = new ilTextInputGUI('description',self::VAR_DESCRIPTION);
		$description->setValue($question->getData()->getDescription());
		$this->addItem($description);

		$title = new ilTextInputGUI('title', self::VAR_TITLE);
		$title->setRequired(true);
		$title->setValue($question->getData()->getQuestionText());
		$this->addItem($title);

		$this->addCommandButton('save', 'Save');
	}

	public function getQuestion() : QuestionDto {
		$question = new QuestionDto();

		$question->setData(new QuestionData($_POST[self::VAR_TITLE],
			                                $_POST[self::VAR_DESCRIPTION],
			                                $_POST[self::VAR_QUESTION],
			                                $_POST[self::VAR_AUTHOR]));

		return $question;
	}
}
