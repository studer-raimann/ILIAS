<?php

namespace ILIAS\AssessmentQuestion\DomainModel\Scoring;


use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use srag\CQRS\Aggregate\AbstractValueObject;

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
     * @var ?int
     */
    protected $points_wrong;    
    
    static function create(?int $points_wrong) : ErrorTextScoringConfiguration
    {
        $object = new ErrorTextScoringConfiguration();
        $object->points_wrong = $points_wrong;
        return $object;
    }
    
    /**
     * @return int
     */
    public function getPointsWrong() : ?int
    {
        return $this->points_wrong;
    }
    
    public function equals(AbstractValueObject $other): bool
    {
        /** @var ErrorTextScoringConfiguration $other */
        return get_class($this) === get_class($other) &&
        $this->points_wrong === $other->points_wrong;
    }
}