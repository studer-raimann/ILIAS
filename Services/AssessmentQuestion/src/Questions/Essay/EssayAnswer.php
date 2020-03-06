<?php

namespace ILIAS\AssessmentQuestion\Questions\Essay;

use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;

/**
 * Class EssayAnswer
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class EssayAnswer extends Answer {
    /**
     * @var string
     */
    protected $text;
    
    public static function create(?string $text = null) : EssayAnswer {
        $object = new EssayAnswer();
        $object->text = $text;
        return $object;
    }
    
    public function getText() : ?string {
        return $this->text;
    }
}