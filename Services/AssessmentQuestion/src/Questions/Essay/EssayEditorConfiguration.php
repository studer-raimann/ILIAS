<?php

namespace ILIAS\AssessmentQuestion\Questions\Essay;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class EssayEditorConfiguration
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class EssayEditorConfiguration extends AbstractConfiguration
{
    /**
     * @var ?int
     */
    protected $max_length;
    
    public static function create(?int $max_length = null) {
        $object = new EssayEditorConfiguration();
        $object->max_length = $max_length;
        return $object;
    }
    
    /**
     * @return int
     */
    public function getMaxLength() : ?int
    {
        return $this->max_length;
    }
    
    /**
     * Compares ValueObjects to each other returns true if they are the same
     *
     * @param AbstractValueObject $other
     *
     * @return bool
     */
    function equals(AbstractValueObject $other) : bool
    {
        /** @var EssayEditorConfiguration $other */
        return get_class($this) === get_class($other) &&
               $this->max_length === $other->max_length;
    }
}