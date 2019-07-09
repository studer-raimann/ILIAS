<?php

class QuestionPlayConfiguration {

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
	public function __construct($presenter_class, $editor_class, $working_time, $shuffle_answer_options) {
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
}