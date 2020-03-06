<?php

namespace ILIAS\AssessmentQuestion\Questions\Matching;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Option\AnswerOptions;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\AbstractScoring;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\EmptyScoringDefinition;
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
    
    public function score(Answer $answer): float
    {
        $matches = [];
        $wrong_deduction = $this->question->getPlayConfiguration()->getScoringConfiguration()->getWrongDeduction();
        
        foreach ($this->question->getPlayConfiguration()->getEditorConfiguration()->getMatches() as $match) {
            $key = $match[MatchingEditor::VAR_MATCH_DEFINITION] . '-' . $match[MatchingEditor::VAR_MATCH_TERM];
            $matches[$key] = intval($match[MatchingEditor::VAR_MATCH_POINTS]);
        };
        
        $score = 0;
        
        foreach ($answer->getMatches() as $given_match) {
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
        
        return $score;
    }

    public function getBestAnswer(): Answer
    {
        $matches = [];
        
        foreach ($this->question->getPlayConfiguration()->getEditorConfiguration()->getMatches() as $match) {
            $matches[] = $match[MatchingEditor::VAR_MATCH_DEFINITION] . '-' . $match[MatchingEditor::VAR_MATCH_TERM];
        };
        
        return new MatchingAnswer($matches);
    }
    
    protected function calculateMaxScore()
    {
        $this->max_score = 0;
        
        foreach ($this->question->getPlayConfiguration()->getEditorConfiguration()->getMatches() as $match) {
            $this->max_score += intval($match[MatchingEditor::VAR_MATCH_POINTS]);
        };        
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
        return MatchingScoringConfiguration::create(empty($_POST[self::VAR_WRONG_DEDUCTION]) ? null : floatval($_POST[self::VAR_WRONG_DEDUCTION]));
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