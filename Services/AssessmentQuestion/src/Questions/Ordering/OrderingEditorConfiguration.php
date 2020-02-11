<?php

namespace ILIAS\AssessmentQuestion\Questions\Ordering;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class OrderingEditorConfiguration
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class OrderingEditorConfiguration extends AbstractConfiguration
{
    /**
     * @var ?bool
     */
    protected $vertical;
    /**
     * @var ?int
     */
    protected $minimum_size;
    
    public static function create(
        ?bool $vertical = null, 
        ?int $minimum_size = null) : OrderingEditorConfiguration
    {
        $object = new OrderingEditorConfiguration();
        $object->vertical = $vertical;
        $object->minimum_size = $minimum_size;
        return $object;
    }
    
    /**
     * @return boolean
     */
    public function isVertical() : ?bool
    {
        return $this->vertical;
    }

    /**
     * @return int
     */
    public function getMinimumSize() : ?int
    {
        return $this->minimum_size;
    }

    public function equals(AbstractValueObject $other): bool
    {
        /** @var OrderingEditorConfiguration $other */
        return get_class($this) === get_class($other) &&
               $this->isVertical() === $other->isVertical() &&
               $this->getMinimumSize() === $other->getMinimumSize();
    }
}