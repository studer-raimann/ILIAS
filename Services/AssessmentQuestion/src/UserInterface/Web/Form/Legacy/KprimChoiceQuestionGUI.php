<?php

namespace ILIAS\AssessmentQuestion\UserInterface\Web\Form\Legacy;

use ILIAS\AssessmentQuestion\DomainModel\QuestionPlayConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\KprimChoiceScoring;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\KprimChoiceScoringConfiguration;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\KprimChoiceEditor;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\KprimChoiceEditorConfiguration;

/**
 * Class MultipleChoiceQuestionGUI
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class KprimChoiceQuestionGUI extends LegacyFormGUIBase {
    protected function createDefaultPlayConfiguration(): QuestionPlayConfiguration
    {
        return QuestionPlayConfiguration::create
        (
            new KprimChoiceEditorConfiguration(),
            new KprimChoiceScoringConfiguration()
            );
    }
    
    protected function readPlayConfiguration(): QuestionPlayConfiguration
    {
        return QuestionPlayConfiguration::create(
            KprimChoiceEditor::readConfig(),
            KprimChoiceScoring::readConfig());
    }

    protected function initiatePlayConfiguration(?QuestionPlayConfiguration $play): void
    {
        foreach (KprimChoiceEditor::generateFields($play->getEditorConfiguration()) as $field) {
            $this->addItem($field);
        }
        
        foreach (KprimChoiceScoring::generateFields($play->getScoringConfiguration()) as $field) {
            $this->addItem($field);
        }
    }
}
