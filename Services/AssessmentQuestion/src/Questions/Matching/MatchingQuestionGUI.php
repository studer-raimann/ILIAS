<?php

namespace ILIAS\AssessmentQuestion\Questions\Matching;

use ILIAS\AssessmentQuestion\DomainModel\QuestionPlayConfiguration;
use ILIAS\AssessmentQuestion\UserInterface\Web\Form\QuestionFormGUI;

/**
 * Class MatchingQuestionGUI
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class MatchingQuestionGUI extends QuestionFormGUI {
    protected function createDefaultPlayConfiguration(): QuestionPlayConfiguration
    {
        return QuestionPlayConfiguration::create(
            MatchingEditorConfiguration::create(),
            MatchingScoringConfiguration::create());
    }
    
    protected function readPlayConfiguration(): QuestionPlayConfiguration
    {
        return QuestionPlayConfiguration::create(
            MatchingEditor::readConfig(),
            MatchingScoring::readConfig());
    }
    
    protected function initiatePlayConfiguration(?QuestionPlayConfiguration $play): void
    {
        foreach (MatchingScoring::generateFields($play->getScoringConfiguration()) as $field) {
            $this->addItem($field);
        }
        
        foreach (MatchingEditor::generateFields($play->getEditorConfiguration()) as $field) {
            $this->addItem($field);
        }
    }
}
