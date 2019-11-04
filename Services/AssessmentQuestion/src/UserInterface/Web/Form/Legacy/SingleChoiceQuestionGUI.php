<?php

namespace ILIAS\AssessmentQuestion\UserInterface\Web\Form\Legacy;

use ILIAS\AssessmentQuestion\DomainModel\QuestionPlayConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\MultipleChoiceScoringConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\MultipleChoiceScoringDefinition;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\ImageAndTextDisplayDefinition;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\MultipleChoiceEditor;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\MultipleChoiceEditorConfiguration;
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
class SingleChoiceQuestionGUI extends LegacyFormGUIBase {
	protected function createDefaultPlayConfiguration(): QuestionPlayConfiguration
	{
	    return QuestionPlayConfiguration::create
	    (
	        MultipleChoiceEditorConfiguration::create(false, 1),
	        new MultipleChoiceScoringConfiguration());
	}
	
	protected function readPlayConfiguration(): QuestionPlayConfiguration
	{
	    return QuestionPlayConfiguration::create(
	        MultipleChoiceEditor::readConfig(),
	        new MultipleChoiceScoringConfiguration());
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
	    $definitions = array_merge(ImageAndTextDisplayDefinition::getFields($play),
	                               MultipleChoiceScoringDefinition::getFields($play));
	    
	    $definitions = $this->renameColumn($definitions, 
	                                       MultipleChoiceScoringDefinition::VAR_MCSD_SELECTED, 
	                                       $this->lang->txt('asq_label_points'));
	    
	    $definitions = $this->hideColumn($definitions, MultipleChoiceScoringDefinition::VAR_MCSD_UNSELECTED, 0);
	    
	    return $definitions;
	}
}