<?php

namespace ILIAS\AssessmentQuestion\Questions\ErrorText;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;

/**
 * Class ErrorTextScoringConfiguration
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ErrorTextScoringConfiguration extends AbstractConfiguration {
    /**
     * @var ?float
     */
    protected $points_wrong;    
    
    static function create(?float $points_wrong = null) : ErrorTextScoringConfiguration
    {
        $object = new ErrorTextScoringConfiguration();
        $object->points_wrong = $points_wrong;
        return $object;
    }
    
    /**
     * @return int
     */
    public function getPointsWrong() : ?float
    {
        return $this->points_wrong;
    }
}