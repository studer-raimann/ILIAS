<?php

namespace ILIAS\AssessmentQuestion\Questions\Ordering;

use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;

/**
 * Class OrderingAnswer
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class OrderingAnswer extends Answer {
    /**
     * @var ?int[]
     */
    protected $selected_order;
    
    public static function create(?array $selected_order = null) : OrderingAnswer {
        $object = new OrderingAnswer();
        $object->selected_order = $selected_order;
        return $object;
    }
    
    public function getSelectedOrder() {
        return $this->selected_order;
    }
}