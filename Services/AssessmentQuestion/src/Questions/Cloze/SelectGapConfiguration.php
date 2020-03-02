<?php

namespace ILIAS\AssessmentQuestion\Questions\Cloze;

/**
 * Class SelectGapConfiguration
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class SelectGapConfiguration extends ClozeGapConfiguration {
    /**
     * @var ?ClozeGapItem[]
     */
    protected $items;
    
    public static function Create(array $items = []) {
        $object = new SelectGapConfiguration();
        $object->items = $items;
        return $object;
    }
    
    /**
     * @return ?array
     */
    public function getItems()
    {
        return $this->items;
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
}