<?php

namespace ILIAS\AssessmentQuestion\DomainModel;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class QuestionPlayConfiguration
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class QuestionPlayConfiguration extends AbstractValueObject {
	/**
	 * @var AbstractConfiguration
	 */
	protected $presenter_configuration;

	/**
	 * @var AbstractConfiguration
	 */
	protected $editor_configuration;

	/**
	 * @var AbstractConfiguration
	 */
	protected $scoring_configuration;

    /**
     * @param AbstractConfiguration $editor_configuration
     * @param AbstractConfiguration $scoring_configuration
     * @param AbstractConfiguration $presenter_configuration
     * @return QuestionPlayConfiguration
     */
	public static function create(
	    AbstractConfiguration $editor_configuration = null,
		AbstractConfiguration $scoring_configuration = null,
		AbstractConfiguration $presenter_configuration = null
	) : QuestionPlayConfiguration {
		$object = new QuestionPlayConfiguration();
		$object->editor_configuration = $editor_configuration;
		$object->presenter_configuration = $presenter_configuration;
		$object->scoring_configuration = $scoring_configuration;
		return $object;
	}

	/**
	 * @return AbstractValueObject
	 */
	public function getEditorConfiguration(): ?AbstractConfiguration {
		return $this->editor_configuration;
	}

	/**
	 * @return AbstractValueObject
	 */
	public function getPresenterConfiguration(): ?AbstractConfiguration {
		return $this->presenter_configuration;
	}

	/**
	 * @return AbstractValueObject
	 */
	public function getScoringConfiguration(): ?AbstractConfiguration {
		return $this->scoring_configuration;
	}

    /**
     * @param AbstractValueObject $other
     *
     * @return bool
     */
    public function equals(AbstractValueObject $other): bool
    {
        /** @var QuestionPlayConfiguration $other */
        return get_class($this) === get_class($other) &&
               AbstractValueObject::isNullableEqual(
        	        $this->getEditorConfiguration(),
	                $other->getEditorConfiguration()) &&
               AbstractValueObject::isNullableEqual(
               	    $this->getPresenterConfiguration(),
                    $other->getPresenterConfiguration()) &&
               AbstractValueObject::isNullableEqual(
               	    $this->getScoringConfiguration(),
                    $other->getScoringConfiguration());
    }
    
    public function hasAnswerOptions(): bool {
        if (is_null($this->getScoringConfiguration()) || is_null($this->getEditorConfiguration())) {
            return false;    
        }
        
        $sd_class = $this->getScoringConfiguration()->configurationFor()::getScoringDefinitionClass();
        $dd_class = $this->getEditorConfiguration()->configurationFor()::getDisplayDefinitionClass();
        
        
        return (count($dd_class::getFields($this)) + count($sd_class::getFields($this))) > 0;
    }
    
    /**
     * @param Question $question
     */
    public static function isComplete(Question $question) : bool{
        return $question->getPlayConfiguration()->getEditorConfiguration()->configurationFor()::isComplete($question) &&
               $question->getPlayConfiguration()->getScoringConfiguration()->configurationFor()::isComplete($question);
    }
}