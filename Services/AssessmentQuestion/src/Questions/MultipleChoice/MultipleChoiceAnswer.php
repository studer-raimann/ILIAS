<?php

namespace ILIAS\AssessmentQuestion\Questions\MultipleChoice;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class MultipleChoiceAnswer
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class MultipleChoiceAnswer extends AbstractValueObject {
    /**
     * @var int[]
     */
    protected $selected_ids;
    
    public static function create(array $selected_ids) : MultipleChoiceAnswer {
        $object = new MultipleChoiceAnswer();
        $object->selected_ids = $selected_ids;
        return $object;
    }
    
    public function getSelectedIds() : array {
        return $this->selected_ids;
    }
}