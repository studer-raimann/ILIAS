<?php

namespace ILIAS\AssessmentQuestion\Questions\Kprim;

use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;

/**
 * Class KprimChoiceEditor
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class KprimChoiceAnswer extends Answer {
    /**
     * @var int[]
     */
    protected $answers;
    
    public static function create(array $answers) : KprimChoiceAnswer {
        $object = new KprimChoiceAnswer();
        $object->answers = $answers;
        return $object;
    }
    
    public function getAnswerForId(int $id) : bool {
        return $this->answers[$id];
    }
}