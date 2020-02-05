<?php

namespace ILIAS\AssessmentQuestion\UserInterface\Web\Form\Questions;

use ILIAS\AssessmentQuestion\DomainModel\QuestionPlayConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\MatchingScoring;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\MatchingScoringConfiguration;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\MatchingEditor;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\MatchingEditorConfiguration;
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
        return QuestionPlayConfiguration::create
        (
            MatchingEditorConfiguration::create(),
            new MatchingScoringConfiguration()
            );
    }
    
    protected function readPlayConfiguration(): QuestionPlayConfiguration
    {
        return QuestionPlayConfiguration::create(
            MatchingEditor::readConfig(),
            MatchingScoring::readConfig());
    }
    
    protected function initiatePlayConfiguration(?QuestionPlayConfiguration $play): void
    {
        foreach (MatchingEditor::generateFields($play->getEditorConfiguration()) as $field) {
            $this->addItem($field);
        }
        
        foreach (MatchingScoring::generateFields($play->getScoringConfiguration()) as $field) {
            $this->addItem($field);
        }
    }
}
