<?php

namespace ILIAS\AssessmentQuestion\Play\Editor;

use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Question;
use JsonSerializable;
use stdClass;

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

	/**
	 * @var Question
	 */
	private $question;
	/**
	 * @var array
	 */
	private $configuration;


	/**
	 * AbstractEditor constructor.
	 *
	 * @param Question   $question
	 * @param array|null $configuration
	 */
	public function __construct(Question $question, array $configuration = null) {
		$this->question = $question;
		$this->configuration = $configuration;
	}

	/**
	 * @return string
	 */
	abstract public function generateHtml(): string;


	/**
	 * @param JsonSerializable|null $config
	 *
	 * @return array|null
	 */
	public static function generateFields(?JsonSerializable $config): ?array {
		return null;
	}

	/**
	 * @return JsonSerializable|null
	 */
	public static function readConfig() : ?JsonSerializable {
		return null;
	}

	/**
	 * @param stdClass $input
	 *
	 * @return JsonSerializable|null
	 */
	public static function deserialize(stdClass $input) : ?JsonSerializable {
		return null;
	}
}