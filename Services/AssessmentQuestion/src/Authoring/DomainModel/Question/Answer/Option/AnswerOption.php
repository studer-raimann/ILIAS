<?php
namespace ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;


use ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option\Value\AnswerOptionValue;

/**
 * Interface AnswerOption
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class AnswerOption {

	/**
	 * @var string
	 */
	private $option_id;
	/**
	 * @var ?DisplayDefinition
	 */
	private $display_definition;
	/**
	 * @var ?ScoringDefinition
	 */
	private $scoring_definition;

	public function __construct(int $id, ?DisplayDefinition $display_definition, ?ScoringDefinition $scoring_definition)
	{
		$this->option_id = $id;
		$this->display_definition = $display_definition;
		$this->scoring_definition = $scoring_definition;
	}


	/**
	 * @return string
	 */
	public function getOptionId(): string {
		return $this->option_id;
	}


	/**
	 * @return mixed
	 */
	public function getDisplayDefinition() {
		return $this->display_definition;
	}


	/**
	 * @return mixed
	 */
	public function getScoringDefinition() {
		return $this->scoring_definition;
	}

	/**
	 * @return array
	 */
	public function rawValues() : array {
		$dd_fields = $this->display_definition !== null ? $this->display_definition->getValues() : [];
		$sd_fields = $this->scoring_definition !== null ? $this->scoring_definition->getValues() : [];

		return array_merge($dd_fields, $sd_fields);
	}
}
