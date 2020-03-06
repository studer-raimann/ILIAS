<?php

namespace ILIAS\AssessmentQuestion\Questions\Kprim;


use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;

/**
 * Class KprimChoiceScoringConfiguration
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class KprimChoiceScoringConfiguration extends AbstractConfiguration {
    /**
     * @var )float
     */
    protected $points;
    /**
     * @var ?int
     */
    protected $half_points_at;
    
    /**
     * @param int $points
     * @param int $half_points_at
     * @return KprimChoiceScoringConfiguration
     */
    static function create(?float $points = null, ?int $half_points_at = null) : KprimChoiceScoringConfiguration
        {
            $object = new KprimChoiceScoringConfiguration();
            $object->points = $points;
            $object->half_points_at = $half_points_at;
            return $object;
    }
    
    /**
     * @return ?int
     */
    public function getPoints() : ?float
    {
        return $this->points;
    }
    
    /**
     * @return ?int
     */
    public function getHalfPointsAt() : ?int
    {
        return $this->half_points_at;
    }
}