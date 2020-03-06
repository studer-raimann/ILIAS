<?php

namespace ILIAS\AssessmentQuestion\Questions\MultipleChoice;

use ILIAS\AssessmentQuestion\DomainModel\QuestionPlayConfiguration;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\ImageAndTextDisplayDefinition;
use ilHiddenInputGUI;

/**
 * Class SingleChoiceQuestionGUI
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class SingleChoiceQuestionGUI extends ChoiceQuestionGUI {
	protected function createDefaultPlayConfiguration(): QuestionPlayConfiguration
	{
	    return QuestionPlayConfiguration::create
	    (
	        MultipleChoiceEditorConfiguration::create(false, 1),
	        MultipleChoiceScoringConfiguration::create());
	}
	
	protected function initiatePlayConfiguration(?QuestionPlayConfiguration $play): void
	{
	    $fields = MultipleChoiceEditor::generateFields($play->getEditorConfiguration());
	    
	    $hidden = new ilHiddenInputGUI(MultipleChoiceEditor::VAR_MCE_MAX_ANSWERS);
	    $hidden->setValue(1);
	    $fields[MultipleChoiceEditor::VAR_MCE_MAX_ANSWERS] = $hidden;
	    
	    foreach ($fields as $field) {
	        $this->addItem($field);
	    }
	}
	
	protected function getAnswerOptionDefinitions(?QuestionPlayConfiguration $play) : array { 
	    global $DIC;
	    
	    $definitions = array_merge(ImageAndTextDisplayDefinition::getFields($play),
	                               MultipleChoiceScoringDefinition::getFields($play));
	    
	    $definitions[MultipleChoiceScoringDefinition::VAR_MCSD_SELECTED]->setHeader($DIC->language()->txt('asq_label_points'));
	    
	    unset($definitions[MultipleChoiceScoringDefinition::VAR_MCSD_UNSELECTED]);
	    
	    return $definitions;
	}
}
