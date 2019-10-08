<?php

namespace ILIAS\AssessmentQuestion\DomainModel\Answer\Option;

/**
 * Class AnswerOptions
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class AnswerOptions {

	/**
	 * @var array
	 */
	private $options;

	public function __construct() {
		$this->options = [];
	}

	public function addOption(?AnswerOption $option) {
		$this->options[] = $option;
	}


    /**
     * @return AnswerOption[]
     */
	public function getOptions() : array {
		return $this->options;
	}
	
	public static function deserialize(string $json_data) : AnswerOptions {
	    $data = json_decode($json_data);

	    $options = new AnswerOptions();

	    foreach($data as $option) {
	        $aoption = new AnswerOption($option->option_id);
	        $aoption->deserialize($option);
	        $options->addOption($aoption);
	    }
	    
	    return $options;
	}
	
	/**
	 * @param AnswerOptions $other
	 * @return bool
	 */
	public function equals (AnswerOptions $other) : bool {
	    return !is_null($other) &&
	           count($this->options) === count($other->options) &&
	           $this->optionsAreEqual($other);
	}
	
	public function optionsAreEqual(AnswerOptions $other) : bool {
	    /** @var AnswerOption $my_option */
	    foreach ($this->options as $my_option) {
	        $found = false;
	        
	        /** @var AnswerOption $other_option */
	        foreach ($other->options as $other_option) {
	            if ($my_option->getOptionId() === $other_option->getOptionId() &&
	                $my_option->equals($other_option)) {
	                    $found = true;
	                    break;
	                }
	        }
	        
	        if (!$found) {
	            return false;
	        }
	    }
	    
	    return true;
	}
}