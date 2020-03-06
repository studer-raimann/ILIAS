<?php

namespace ILIAS\AssessmentQuestion\Questions\Matching;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;

/**
 * Class MatchingScoringConfiguration
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class MatchingScoringConfiguration extends AbstractConfiguration {
    /**
     * @var ?float
     */
    protected $wrong_deduction;
    
    public static function create(?float $wrong_deduction = null) : MatchingScoringConfiguration {
        $object = new MatchingScoringConfiguration();
        $object->wrong_deduction = $wrong_deduction;
        return $object;
    }
        
    public function getWrongDeduction(): ?float {
        return $this->wrong_deduction;
    }
}