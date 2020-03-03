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
    
    /**
     * @var int
     */
    protected $field_length;
    
    public static function Create(?float $value = null, ?float $upper = null, ?float $lower = null, ?float $points = null, int $field_length = 80) {
        $object = new NumericGapConfiguration();
        $object->value = $value;
        $object->upper = $upper;
        $object->lower = $lower;
        $object->points = $points;
        $object->field_length = $field_length;
        return $object;
    }
    
    /**
     * @return ?float
     */
    public function getValue() : ?float
    {
        return $this->value;
    }
    
    /**
     * @return ?float
     */
    public function getUpper() : ?float
    {
        return $this->upper;
    }
    
    /**
     * @return ?float
     */
    public function getLower() : ?float
    {
        return $this->lower;
    }
    
    /**
     * @return ?int
     */
    public function getPoints() : ?int
    {
        return $this->points;
    }
    
    /**
     * @return int
     */
    public function getFieldLength() : int
    {
        return $this->field_length ?? 77;
    }
}