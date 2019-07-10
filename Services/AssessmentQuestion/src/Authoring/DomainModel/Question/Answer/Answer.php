<?php
namespace ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer;

use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option\AnswerOption;
use ILIAS\AssessmentQuestion\Common\DomainModel\Aggregate\DomainObjectId;
use ILIAS\AssessmentQuestion\Common\DomainModel\Aggregate\Entity;

class Answer implements Entity {

	/**
	 * @var int
	 */
	protected $answerer_id;
	/**
	 * @var string
	 */
	protected $question_id;
	/**
	 * @var string
	 */
	protected $value;

	public function __construct(int $anwerer_id, string $question_id, string $value) {
		$this->answerer_id = $anwerer_id;
		$this->question_id = $question_id;
		$this->value = $value;
	}


	/**
	 * @return int
	 */
	public function getAnswererId(): int {
		return $this->answerer_id;
	}


	/**
	 * @return string
	 */
	public function getQuestionId(): string {
		return $this->question_id;
	}


	/**
	 * @return string
	 */
	public function getValue(): string {
		return $this->value;
	}
}