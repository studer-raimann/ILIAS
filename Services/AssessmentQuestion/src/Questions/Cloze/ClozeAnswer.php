<?php

namespace ILIAS\AssessmentQuestion\Questions\Cloze;

use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;

/**
 * Class ClozeAnswer
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ClozeAnswer extends Answer {
    /**
     * @var ?array
     */
    protected $answers;
    
    public static function create(?array $answers = []) : ClozeAnswer {
        $object = new ClozeAnswer();
        $object->answers = $answers;
        return $object;
    }
    
    public function getAnswers() : ?array {
        return $this->answers;
    }
}