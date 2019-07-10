<?php

namespace ILIAS\AssessmentQuestion\Authoring\UserInterface\Web\Form;
use ilCheckboxInputGUI;
use ilDurationInputGUI;
use ilHiddenInputGUI;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Question;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\QuestionData;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\QuestionDto;
use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\QuestionPlayConfiguration;
use ilImageFileInputGUI;
use ilNumberInputGUI;
use \ilPropertyFormGUI;
use ilTextAreaInputGUI;
use \ilTextInputGUI;
use mysql_xdevapi\Exception;
use srag\CustomInputGUIs\SrAssessment\MultiLineInputGUI\MultiLineInputGUI;

class QuestionFormGUI extends ilPropertyFormGUI {
	const VAR_AGGREGATE_ID = 'aggregate_id';

	const VAR_TITLE = 'title';
	const VAR_AUTHOR = 'author';
	const VAR_DESCRIPTION = 'description';
	const VAR_QUESTION = 'question';

	const VAR_EDITOR = 'editor';
	const VAR_PRESENTER = 'presenter';
	const VAR_SHUFFLE = 'shuffle';
	const VAR_WORKING_TIME = 'working_time';

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
	private function initForm(QuestionDto $question) {
		$id = new ilHiddenInputGUI(self::VAR_AGGREGATE_ID);
		$id->setValue($question->getId());
		$this->addItem($id);

		$this->initQuestionDataConfiguration($question);

		$this->initiatePlayConfiguration($question);

		$this->addCommandButton('save', 'Save');
	}

	public function getQuestion() : QuestionDto {
		$question = new QuestionDto();
		$question->setId($_POST[self::VAR_AGGREGATE_ID]);

		$question->setData($this->readQuestionData());

		$question->setPlayConfiguration($this->readPlayConfiguration());

		return $question;
	}

	/**
	 * @param QuestionDto $question
	 */
	private function initQuestionDataConfiguration(QuestionDto $question): void {
		$title = new ilTextInputGUI('title', self::VAR_TITLE);
		$title->setRequired(true);
		$title->setValue($question->getData()->getTitle());
		$this->addItem($title);

		$author = new ilTextInputGUI('author', self::VAR_AUTHOR);
		$author->setRequired(true);
		$author->setValue($question->getData()->getAuthor());
		$this->addItem($author);

		$description = new ilTextInputGUI('description', self::VAR_DESCRIPTION);
		$description->setValue($question->getData()->getDescription());
		$this->addItem($description);

		$question_text = new ilTextAreaInputGUI('question', self::VAR_QUESTION);
		$question_text->setRequired(true);
		$question_text->setValue($question->getData()->getQuestionText());
		$question_text->setRows(10);
		$this->addItem($question_text);
	}

	/**
	 * @param $question
	 */
	private function initiatePlayConfiguration(QuestionDto $question): void {
		$editor = new ilTextInputGUI('editor', self::VAR_EDITOR);
		$this->addItem($editor);

		$presenter = new ilTextInputGUI('presenter', self::VAR_PRESENTER);
		$this->addItem($presenter);

		$working_time = new ilDurationInputGUI('working_time', self::VAR_WORKING_TIME);
		$working_time->setShowHours(TRUE);
		$working_time->setShowMinutes(TRUE);
		$working_time->setShowSeconds(TRUE);
		$this->addItem($working_time);

		$shuffle = new ilCheckboxInputGUI('shuffle', self::VAR_SHUFFLE);
		$shuffle->setValue(1);
		$this->addItem($shuffle);

		if ($question->getPlayConfiguration() !== null) {
			$editor->setValue($question->getPlayConfiguration()->getEditorClass());
			$presenter->setValue($question->getPlayConfiguration()->getPresenterClass());
			$working_time->setHours($question->getPlayConfiguration()->getWorkingTime() / 3600);
			$working_time->setMinutes($question->getPlayConfiguration()->getWorkingTime() / 60);
			$working_time->setSeconds($question->getPlayConfiguration()->getWorkingTime() % 60);
			$shuffle->setChecked($question->getPlayConfiguration()->isShuffleAnswerOptions());
		}
	}

	/**
	 * @return QuestionData
	 */
	private function readQuestionData(): QuestionData {
		return new QuestionData(
			$_POST[self::VAR_TITLE],
			$_POST[self::VAR_DESCRIPTION],
			$_POST[self::VAR_QUESTION],
			$_POST[self::VAR_AUTHOR]
		);
	}

	/**
	 * @return QuestionPlayConfiguration
	 */
	private function readPlayConfiguration(): QuestionPlayConfiguration {
		/** @var ilDurationInputGUI $working_time_item */
		$working_time_item = $this->getItemByPostVar(self::VAR_WORKING_TIME);


		return new QuestionPlayConfiguration(
			$_POST[self::VAR_PRESENTER],
			$_POST[self::VAR_EDITOR],
			$this->readWorkingTime($_POST[self::VAR_WORKING_TIME]),
			filter_var($_POST[self::VAR_SHUFFLE], FILTER_VALIDATE_BOOLEAN)
		);
	}

	private function readWorkingTime($postval) : int {
		$HOURS = 'hh';
		$MINUTES = 'mm';
		$SECONDS = 'ss';

		if (
			is_array($postval) &&
			array_key_exists($HOURS, $postval) &&
			array_key_exists($MINUTES, $postval) &&
			array_key_exists($SECONDS, $postval)) {
			return $postval[$HOURS] * 60 * 60 + $postval[$MINUTES] * 60 + $postval[$SECONDS];
		} else {
			throw new Exception("This should be impossible, please fix implementation");
		}
	}
}
