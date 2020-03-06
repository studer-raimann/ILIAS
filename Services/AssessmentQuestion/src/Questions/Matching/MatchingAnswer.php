<?php

namespace ILIAS\AssessmentQuestion\Questions\Matching;

use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;

/**
 * Class MatchingAnswer
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author Adrian Lüthi <al@studer-raimann.ch>
 * @author Björn Heyser <bh@bjoernheyser.de>
 * @author Martin Studer <ms@studer-raimann.ch>
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class MatchingAnswer extends Answer {
    /**
     * @var string[]
     */
    protected $matches;
    
    public static function create(?array $matches) : MatchingAnswer {
        $object = new MatchingAnswer();
        $object->matches = $matches;
        return $object;
    }
    
    public function getMatches() : ?array {
        return $this->matches;
    }
    
    public function getAnswerString() : string {
        return implode(';', $this->matches);
    }
}