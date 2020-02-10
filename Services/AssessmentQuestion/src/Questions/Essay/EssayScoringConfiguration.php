<?php

namespace ILIAS\AssessmentQuestion\Questions\Essay;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class EssayScoringConfiguration
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class EssayScoringConfiguration extends AbstractConfiguration {
    /**
     * @var int
     */
    protected $matching_mode;
    
    /**
     * @var int
     */
    protected $scoring_mode;
    
    /**
     * @var ?int
     */
    protected $points;
    
    public static function create(int $matching_mode = EssayScoring::TM_CASE_INSENSITIVE,
                                  int $scoring_mode = EssayScoring::SCORING_MANUAL,
                                  ?int $points = null) : EssayScoringConfiguration {
        
        $object = new EssayScoringConfiguration();
        
        $object->matching_mode = $matching_mode;
        $object->scoring_mode = $scoring_mode;
        $object->points = $points;
        
        return $object;
    }
    
    public function getMatchingMode() {
        return $this->matching_mode;
    }
    
    public function getScoringMode() {
        return $this->scoring_mode;
    }
    
    public function getPoints() {
        return $this->points;
    }
    
    public function equals(AbstractValueObject $other): bool
    {
        /** @var EssayScoringConfiguration $other */
        return get_class($this) === get_class($other) &&
               $this->matching_mode === $other->matching_mode &&
               $this->scoring_mode === $other->scoring_mode &&
               $this->points === $other->points;
    }
}