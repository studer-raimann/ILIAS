<?php

namespace ILIAS\AssessmentQuestion\Play\Editor;

use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option\DisplayDefinition;

/**
 * Class MultipleChoiceEditorDisplayDefinition
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class MultipleChoiceEditorDisplayDefinition extends DisplayDefinition {

	/**
	 * @var string
	 */
	private $text;
	/**
	 * @var string
	 */
	private $image;

	public function __construct(string $text, string $timage) {
		$this->text = $text;
		$this->image = $timage;
	}


	/**
	 * @return string
	 */
	public function getText(): string {
		return $this->text;
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