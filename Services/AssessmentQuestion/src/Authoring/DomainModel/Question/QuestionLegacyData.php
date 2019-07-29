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
class QuestionLegacyData implements JsonSerializable {
	/**
	 * @var int;
	 */
	private $container_obj_id;
	/**
	 * @var int
	 */
	private $answer_type_id;

	/**
	 * QuestionLegacyData constructor.
	 *
	 * @param int $answer_type_id
	 * @param int $container_obj_id
	 */
	public function __construct(int $answer_type_id, int $container_obj_id) {
		$this->answer_type_id = $answer_type_id;
		$this->container_obj_id = $container_obj_id;
	}

	/**
	 * @return int
	 */
	public function getContainerObjId(): int {
		return $this->container_obj_id;
	}

	/**
	 * @return int
	 */
	public function getAnswerTypeId(): int {
		return $this->answer_type_id;
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