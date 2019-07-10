<?php

namespace ILIAS\AssessmentQuestion\Authoring\DomainModel\Question;

use JsonSerializable;

/**
 * Class QuestionPlayConfiguration
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question
 *
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class QuestionPlayConfiguration implements JsonSerializable{

	/**
	 * @var string
	 */
	private $presenter_class;
	/**
	 * @var string
	 */
	private $editor_class;
	/**
	 * @var int Working time in seconds
	 */
	private $working_time;
	/**
	 * @var bool
	 */
	private $shuffle_answer_options;


	/**
	 * QuestionPlayConfiguration constructor.
	 *
	 * @param $presenter_class
	 * @param $editor_class
	 * @param $working_time
	 * @param $shuffle_answer_options
	 */
	public function __construct(string $presenter_class, string $editor_class, int $working_time, bool $shuffle_answer_options) {
		$this->presenter_class = $presenter_class;
		$this->editor_class = $editor_class;
		$this->working_time = $working_time;
		$this->shuffle_answer_options = $shuffle_answer_options;
	}

	/**
	 * @return string
	 */
	public function getPresenterClass(): string {
		return $this->presenter_class;
	}


	/**
	 * @return string
	 */
	public function getEditorClass(): string {
		return $this->editor_class;
	}


	/**
	 * @return int
	 */
	public function getWorkingTime(): int {
		return $this->working_time;
	}

	/**
	 * @return bool
	 */
	public function isShuffleAnswerOptions(): bool {
		return $this->shuffle_answer_options;
	}


	/**
	 * Specify data which should be serialized to JSON
	 *
	 * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return get_object_vars($this);
	}
}