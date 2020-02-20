<?php

namespace ILIAS\AssessmentQuestion\Questions\TextSubset;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class TextSubsetScoringConfiguration
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class TextSubsetScoringConfiguration extends AbstractConfiguration {
    /**
     * @var ?int
     */
    protected $text_matching;
    
    static function create(?int $text_matching = null) : TextSubsetScoringConfiguration
    {
        $object = new TextSubsetScoringConfiguration();
        $object->text_matching = $text_matching;
        return $object;
    }
    
    /**
     * @return ?int
     */
    public function getTextMatching() : ?int
    {
        return $this->text_matching;
    }
    
    public function equals(AbstractValueObject $other): bool
    {
        /** @var TextSubsetScoringConfiguration $other */
        return get_class($this) === get_class($other) &&
               $this->text_matching === $other->text_matching;
    }
}