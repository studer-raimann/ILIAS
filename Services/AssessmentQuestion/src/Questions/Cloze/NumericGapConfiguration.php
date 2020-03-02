<?php

namespace ILIAS\AssessmentQuestion\Questions\Cloze;

/**
 * Class NumericGapConfiguration
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class NumericGapConfiguration extends ClozeGapConfiguration {
    /**
     * @var ?float
     */
    protected $value;
    
    /**
     * @var ?float
     */
    protected $upper;
    
    /**
     * @var ?float
     */
    protected $lower;
    
    /**
     * @var ?int
     */
    protected $points;
    
    public static function Create(?float $value = null, ?float $upper = null, ?float $lower = null, ?float $points = null) {
        $object = new NumericGapConfiguration();
        $object->value = $value;
        $object->upper = $upper;
        $object->lower = $lower;
        $object->points = $points;
        return $object;
    }
    
    /**
     * @return ?float
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * @return ?float
     */
    public function getUpper()
    {
        return $this->upper;
    }
    
    /**
     * @return ?float
     */
    public function getLower()
    {
        return $this->lower;
    }
    
    /**
     * @return ?int
     */
    public function getPoints()
    {
        return $this->points;
    }
}