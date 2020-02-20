<?php

namespace ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Abstract Class AbstractEditor
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
abstract class AbstractEditor {
	const EDITOR_DEFINITION_SUFFIX = 'DisplayDefinition';

	/**
	 * @var QuestionDto
	 */
	protected $question;
	/**
	 * @var AbstractValueObject
	 */
	protected $answer;
	
	/**
	 * AbstractEditor constructor.
	 *
	 * @param QuestionDto   $question
	 * @param array|null $configuration
	 */
	public function __construct(QuestionDto $question) {
		$this->question = $question;
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		    $this->setAnswer($this->readAnswer());
		}
	}

	/**
	 * @return string
	 */
	abstract public function generateHtml(): string;

	/**
	 * @return Answer
	 */
	abstract public function readAnswer() : AbstractValueObject;

	/**
	 * @param AbstractValueObject $answer
	 */
	public function setAnswer(AbstractValueObject $answer) : void {
	    $this->answer = $answer;
	}

    /**
     * @param AbstractConfiguration|null $config
     *
     * @return array|null
     */
	public static function generateFields(?AbstractConfiguration $config): ?array {
		return [];
	}

	public static abstract function readConfig();
	
	public static abstract function isComplete(Question $question): bool;
	/**
	 * @return string
	 */
	static function getDisplayDefinitionClass() : string {
		return get_called_class() . self::EDITOR_DEFINITION_SUFFIX;
	}
}