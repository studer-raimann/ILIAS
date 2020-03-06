<?php

namespace ILIAS\AssessmentQuestion\Questions\ErrorText;

use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;

/**
 * Class ErrorTextEditor
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ErrorTextAnswer extends Answer {
    /**
     * @var int[]
     */
    protected $selected_word_indexes;
    
    public static function create(array $selected_word_indexes = []) : ErrorTextAnswer {
        $object = new ErrorTextAnswer();
        $object->selected_word_indexes = $selected_word_indexes;
        return $object;
    }
    
    public function getSelectedWordIndexes() : array {
        return $this->selected_word_indexes;
    }
    
    public function getPostString() : string {
        return implode(',', $this->selected_word_indexes);
    }
}