<?php

namespace ILIAS\AssessmentQuestion\Questions\Matching;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\AnswerScoreDto;
use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Option\AnswerOptions;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\AbstractScoring;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\EmptyScoringDefinition;
use ilNumberInputGUI;

/**
 * Class MultipleChoiceScoring
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author Adrian Lüthi <al@studer-raimann.ch>
 * @author Björn Heyser <bh@bjoernheyser.de>
 * @author Martin Studer <ms@studer-raimann.ch>
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class MatchingScoring extends AbstractScoring
{
    const VAR_WRONG_DEDUCTION = 'ms_wrong_deduction';
    
    public function score(Answer $answer): AnswerScoreDto
    {
        $matches = [];
        $max_score = 0;
        $wrong_deduction = $this->question->getPlayConfiguration()->getScoringConfiguration()->getWrongDeduction();
        
        foreach ($this->question->getPlayConfiguration()->getEditorConfiguration()->getMatches() as $match) {
            $key = $match[MatchingEditor::VAR_MATCH_DEFINITION] . '-' . $match[MatchingEditor::VAR_MATCH_TERM];
            $value = intval($match[MatchingEditor::VAR_MATCH_POINTS]);
            $max_score += $value;
            $matches[$key] = $value;
        };
        
        $score = 0;
        
        foreach ($answer->getValue()->getMatches() as $given_match) {
            if(array_key_exists($given_match, $matches)) {
                $score += $matches[$given_match];
            }
            else if (!is_null($wrong_deduction)) {
                $score -= $wrong_deduction;
            }
        }
        
        if ($score < 0) {
            $score = 0;
        }
        
        return $this->createScoreDto($answer, $max_score, $score, $this->getAnswerFeedbackType($score,$max_score));
    }

    
    /**
     * @return array|null
     */
    public static function generateFields(?AbstractConfiguration $config, AnswerOptions $options = null): ?array {
        global $DIC;
        
        $fields = [];
        
        $wrong_deduction = new ilNumberInputGUI($DIC->language()->txt('asq_label_wrong_deduction'), self::VAR_WRONG_DEDUCTION);
        $wrong_deduction->setSize(2);
        $fields[self::VAR_WRONG_DEDUCTION] = $wrong_deduction;
        
        if (!is_null($config)) {
            $wrong_deduction->setValue($config->getWrongDeduction());
        }
        
        return $fields;
    }
    
    public static function readConfig()
    {
        return MatchingScoringConfiguration::create(
            intval($_POST[self::VAR_WRONG_DEDUCTION]));
    }  
    
    /**
     * @return string
     */
    public static function getScoringDefinitionClass() : string
    {
        return EmptyScoringDefinition::class;
    }
    
    public static function isComplete(Question $question): bool
    {
        return true;
    }
}