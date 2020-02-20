<?php

namespace ILIAS\AssessmentQuestion\Questions\TextSubset;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class TextSubsetAnswer
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class TextSubsetAnswer extends AbstractValueObject {
    /**
     * @var ?int[]
     */
    protected $answers;
    
    public static function create(?array $answers = null) {
        $object = new TextSubsetAnswer();
        $object->answers = $answers;
        return $object;
    }
    
    public function getAnswers() : ?array {
        return $this->answers;
    }
}