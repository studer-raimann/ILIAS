<?php

namespace ILIAS\AssessmentQuestion\Questions\ErrorText;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Option\AnswerOptions;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\AbstractScoring;
use ilNumberInputGUI;

/**
 * Class ErrorTextScoring
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ErrorTextScoring extends AbstractScoring {
    const VAR_POINTS_WRONG = 'ets_points_wrong';
    
    function score(Answer $answer) : float {
        $reached_points = 0.0;
        
        $selected_words = $answer->getSelectedWordIndexes();
        $correct_words = [];
        
        foreach ($this->question->getAnswerOptions()->getOptions() as $option) {
            /** @var ErrorTextScoringDefinition $scoring_definition */
            $scoring_definition = $option->getScoringDefinition();
            
            if (in_array($scoring_definition->getWrongWordIndex(), $selected_words)) {           
                //multiple words '(( ))'
                if ($scoring_definition->getWrongWordLength() > 1) {
                    $correct = true;
                    
                    for ($i = 0; $i < $scoring_definition->getWrongWordLength(); $i++) {
                        
                        $current = $scoring_definition->getWrongWordIndex() + $i;
                        
                        if (!in_array($current, $selected_words)) {
                            $correct = false;
                            break;
                        }
                    }       
                    
                    if ($correct) {
                        for ($i = 0; $i < $scoring_definition->getWrongWordLength(); $i++) {
                            $correct_words[] = $scoring_definition->getWrongWordIndex() + $i;
                        } 
                        $reached_points += $scoring_definition->getPoints();
                    }
                }
                // single word '#'
                else 
                {
                    $correct_words[] = $scoring_definition->getWrongWordIndex();
                    $reached_points += $scoring_definition->getPoints();                    
                }
            }
        }
        
        //deduct wrong selections
        $reached_points -= count(array_diff($selected_words, $correct_words));
        
        return $reached_points;
    }
    
    protected function calculateMaxScore()
    {
        $this->max_score = 0.0;
        
        foreach ($this->question->getAnswerOptions()->getOptions() as $option) {
            /** @var ErrorTextScoringDefinition $scoring_definition */
            $scoring_definition = $option->getScoringDefinition();
            $this->max_score += $scoring_definition->getPoints();
        }
    }
    
    public function getBestAnswer() : Answer {
        $selected_word_indexes = [];
        
        foreach ($this->question->getAnswerOptions()->getOptions() as $option) {
            /** @var ErrorTextScoringDefinition $scoring_definition */
            $scoring_definition = $option->getScoringDefinition();
            
            for ($i = 0; $i < $scoring_definition->getWrongWordLength(); $i++) {
                
                $selected_word_indexes[] = $scoring_definition->getWrongWordIndex() + $i;
            }
        }
        
        return ErrorTextAnswer::create($selected_word_indexes);
    }
    
    /**
     * @return array|null
     */
    public static function generateFields(?AbstractConfiguration $config, AnswerOptions $options = null): ?array {
        /** @var ErrorTextScoringConfiguration $config */
        global $DIC;
        
        $fields = [];
        
        $points_wrong = new ilNumberInputGUI($DIC->language()->txt('asq_label_points_wrong'), self::VAR_POINTS_WRONG);
        $points_wrong->setSize(6);
        $points_wrong->setRequired(true);
        $points_wrong->setMaxValue(0);
        $points_wrong->setInfo($DIC->language()->txt('asq_info_points_wrong'));
        $fields[self::VAR_POINTS_WRONG] = $points_wrong;
        
        if ($config !== null) {
            $points_wrong->setValue($config->getPointsWrong());
        }
        
        return $fields;
    }
    
    /**
     * @return ?AbstractConfiguration|null
     */
    public static function readConfig() : ?AbstractConfiguration {
        return ErrorTextScoringConfiguration::create(empty($_POST[self::VAR_POINTS_WRONG]) ? null : floatval($_POST[self::VAR_POINTS_WRONG]));
    }
    
    public static function isComplete(Question $question): bool
    {
        /** @var ErrorTextScoringConfiguration $config */
        $config = $question->getPlayConfiguration()->getScoringConfiguration();
        
        if (empty($config->getPointsWrong())) {
            return false;
        }
        
        if (count($question->getAnswerOptions()->getOptions()) < 1) {
            return false;
        }
        
        foreach ($question->getAnswerOptions()->getOptions() as $option) {
            /** @var ErrorTextScoringDefinition $option_config */
            $option_config = $option->getScoringDefinition();
            
            if (empty($option_config->getPoints()) ||
                empty($option_config->getWrongWordIndex() ||
                empty($option_config->getWrongWordLength()))) 
            {
                return false;
            }
        }
        
        return true;
    }
}