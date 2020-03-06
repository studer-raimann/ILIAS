<?php

namespace ILIAS\AssessmentQuestion\Questions\Formula;

use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;

/**
 * Class FormulaAnswer
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class FormulaAnswer extends Answer {
    /**
     * @var ?array
     */
    protected $values;
    
    public static function create(?array $values = null) : FormulaAnswer {
        $object = new FormulaAnswer();
        $object->values = $values;
        return $object;
    }
    
    public function getValues(): ?array {
        return $this->values;
    }
}