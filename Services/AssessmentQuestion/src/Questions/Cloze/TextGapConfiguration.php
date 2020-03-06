<?php

namespace ILIAS\AssessmentQuestion\Questions\Cloze;

use ILIAS\AssessmentQuestion\DomainModel\Scoring\TextScoring;

/**
 * Class TextGapConfiguration
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class TextGapConfiguration extends ClozeGapConfiguration {
    /**
     * @var ?ClozeGapItem[]
     */
    protected $items;

    /**
     * @var int
     */
    protected $field_length;
    
    /**
     * @var int
     */
    protected $matching_method;
    
    public static function Create(array $items = [], int $field_length = 80, int $matching_method = TextScoring::TM_CASE_SENSITIVE) {
        $object = new TextGapConfiguration();
        $object->items = $items;
        $object->field_length = $field_length;
        $object->matching_method = $matching_method;
        return $object;
    }
    
    /**
     * @return ?array
     */
    public function getItems()
    {
        return $this->items;
    }
    
    public function getFieldLength() : int {
        return $this->field_length;
    }
    
    public function getMatchingMethod() : int {
        return $this->matching_method;
    }
    
    /**
     * @return array
     */
    public function getItemsArray(): array {
        $var_array = [];
        
        if (!is_null($this->items)) {
            foreach($this->items as $variable) {
                $var_array[] = $variable->getAsArray();
            }
        }
        
        return $var_array;
    }
    
    public function getMaxPoints(): float
    {
        $gap_max = 0;
        
        /** @var $gap ClozeGapItem */
        foreach($this->items as $gap_item) {
            if ($gap_item->getPoints() > $gap_max) {
                $gap_max = $gap_item->getPoints();
            }
        }
        
        return $gap_max;
    }
}