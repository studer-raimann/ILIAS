<?php

namespace ILIAS\AssessmentQuestion\Questions\Numeric;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class NumericEditor
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class NumericAnswer extends AbstractValueObject {
    /**
     * @var ?float
     */
    protected $value;
    
    public static function create(?float $value = null) : NumericAnswer {
        $object = new NumericAnswer();
        $object->value = $value;
        return $object;
    }
    
    public function getValue() : ?float {
        return $this->value;
    }
}