<?php

namespace ILIAS\AssessmentQuestion\Questions\Cloze;

use ILIAS\AssessmentQuestion\CQRS\Aggregate\AbstractValueObject;

/**
 * Class ClozeGapConfiguration
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ClozeGapConfiguration extends AbstractValueObject {
    const TYPE_TEXT = 'clz_text';
    const TYPE_NUMBER = 'clz_number';
    const TYPE_DROPDOWN = 'clz_dropdown';
    
    /**
     * @var string
     */
    protected $type;
    
    /**
     * @var ?ClozeGapItem[]
     */
    protected $items;
    
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
     * @param string $type
     * @param array $items
     * @return ClozeGapConfiguration
     */
    public static function createText(string $type, array $items) : ClozeGapConfiguration {
        $config = new ClozeGapConfiguration();
        $config->type = $type;
        $config->items = $items;
        return $config;
    }
    
    public static function createNumber(string $type, 
                                        ?float $value = null, 
                                        ?float $upper = null, 
                                        ?float $lower = null, 
                                        ?int $points = null) {
        $config = new ClozeGapConfiguration();
        $config->type = $type;
        $config->value = $value;
        $config->upper = $upper;
        $config->lower = $lower;
        $config->points = $points;
        return $config;
    }
    
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
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

    public function equals(AbstractValueObject $other): bool
    {
        /** @var ClozeGapConfiguration $other */
        return get_class($this) === get_class($other) &&
        $this->type === $other->type &&
        abs($this->value - $other->value) < PHP_FLOAT_EPSILON &&
        abs($this->upper - $other->upper) < PHP_FLOAT_EPSILON &&
        abs($this->lower - $other->lower) < PHP_FLOAT_EPSILON &&
        $this->points === $other->points &&
        $this->itemsEquals($other->items);
    }
    
    /**
     * @param array $items
     * @return bool
     */
    private function itemsEquals(?array $items) : bool
    {
        if (is_null($items) || is_null($this->items)) {
            return is_null($items) && is_null($this->items);
        }
        
        if (count($this->items) !== count($items)) {
            return false;
        }
        
        for ($i = 0; $i < count($items); $i += 1) {
            if(!$this->items[$i]->equals($items[$i])) {
                return false;
            }
        }
        
        return true;
    }
}