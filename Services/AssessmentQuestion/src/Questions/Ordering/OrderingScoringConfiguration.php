<?php

namespace ILIAS\AssessmentQuestion\Questions\Ordering;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use ILIAS\AssessmentQuestion\UserInterface\Web\Form\Config\AnswerOptionForm;

/**
 * Class OrderingScoringConfiguration
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class OrderingScoringConfiguration extends AbstractConfiguration {
    /**
     * @var ?float
     */
    protected $points;
    
    
    static function create(?float $points = null) : OrderingScoringConfiguration
    {
        $object = new OrderingScoringConfiguration();
        $object->points = $points;
        return $object;
    }
    
    /**
     * @return int
     */
    public function getPoints(): ?float
    {
        return $this->points;
    }
    /**
     * @return array
     */
    public function getOptionFormConfig() : array {
        return [AnswerOptionForm::OPTION_ORDER];
    }
}