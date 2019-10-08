<?php

namespace ILIAS\AssessmentQuestion\DomainModel\Scoring;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ilSelectInputGUI;

/**
 * Class TextSubsetScoring
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class TextSubsetScoring extends AbstractScoring
{
    
    const VAR_TEXT_MATCHING = 'tss_text_matching';
    const TM_CASE_INSENSITIVE = 1;
    const TM_CASE_SENSITIVE = 2;
    const TM_LEVENSHTEIN_1 = 3;
    const TM_LEVENSHTEIN_2 = 4;
    const TM_LEVENSHTEIN_3 = 5;
    const TM_LEVENSHTEIN_4 = 6;
    const TM_LEVENSHTEIN_5 = 7;
    
    /**
     * {@inheritDoc}
     * @see \ILIAS\AssessmentQuestion\DomainModel\Scoring\AbstractScoring::score()
     */
    function score(Answer $answer) : int
    {
        /** @var TextSubsetScoringConfiguration $scoring_conf */
        $scoring_conf = $this->question->getPlayConfiguration()->getScoringConfiguration();
        
        $answer_arr = json_decode($answer->getValue(), true);
        
        switch ($scoring_conf->getTextMatching()) {
            case self::TM_CASE_INSENSITIVE:
                return $this->caseInsensitiveScoring($answer_arr);
            case self::TM_CASE_SENSITIVE:
                return $this->caseSensitiveScoring($answer_arr);
            case self::TM_LEVENSHTEIN_1:
                return $this->levenshteinScoring($answer_arr, 1);
            case self::TM_LEVENSHTEIN_2:
                return $this->levenshteinScoring($answer_arr, 2);
            case self::TM_LEVENSHTEIN_3:
                return $this->levenshteinScoring($answer_arr, 3);
            case self::TM_LEVENSHTEIN_4:
                return $this->levenshteinScoring($answer_arr, 4);
            case self::TM_LEVENSHTEIN_5:
                return $this->levenshteinScoring($answer_arr, 5);
        }
        
        return 1;
    }
    
    /**
     * @param array $answer_arr
     * @return int
     */
    private function caseInsensitiveScoring(array $answer_arr) : int {
        $score = 0;
        
        foreach ($answer_arr as $result) {
            foreach ($this->question->getAnswerOptions()->getOptions() as $correct) {
                if (strtoupper($correct->getScoringDefinition()->getText()) === strtoupper($result)) {
                    $score += $correct->getScoringDefinition()->getPoints();
                    break;
                }
            }
        }
        
        return $score;
    }
    
    /**
     * @param array $answer_arr
     * @return int
     */
    private function caseSensitiveScoring(array $answer_arr) : int {
        $score = 0;
        
        foreach ($answer_arr as $result) {
            foreach ($this->question->getAnswerOptions()->getOptions() as $correct) {
                if ($correct->getScoringDefinition()->getText() === $result) {
                    $score += $correct->getScoringDefinition()->getPoints();
                    break;
                }
            }
        }
        
        return $score;
    }
    
    /**
     * @param array $answer_arr
     * @param int $distance
     * @return int
     */
    private function levenshteinScoring(array $answer_arr, int $distance) : int {
        $score = 0;
        
        foreach ($answer_arr as $result) {
            foreach ($this->question->getAnswerOptions()->getOptions() as $correct) {
                if (levenshtein($correct->getScoringDefinition()->getText(), $result) <= $distance) {
                    $score += $correct->getScoringDefinition()->getPoints();
                    break;
                }
            }
        }
        
        return $score;
    }
    
    /**
     * @param AbstractConfiguration|null $config
     *
     * @return array|null
     */
    public static function generateFields(?AbstractConfiguration $config): ?array {
        /** @var TextSubsetScoringConfiguration $config */
        global $DIC;
        
        $fields = [];
        
        $text_matching = new ilSelectInputGUI($DIC->language()->txt('asq_label_text_matching'), self::VAR_TEXT_MATCHING);
        $text_matching->setOptions(
            [self::TM_CASE_INSENSITIVE => $DIC->language()->txt('asq_option_case_insensitive'), 
                self::TM_CASE_SENSITIVE => $DIC->language()->txt('asq_option_case_sensitive'),
                self::TM_LEVENSHTEIN_1 => $DIC->language()->txt('asq_option_levenshtein_1'),
                self::TM_LEVENSHTEIN_2 => $DIC->language()->txt('asq_option_levenshtein_2'),
                self::TM_LEVENSHTEIN_3 => $DIC->language()->txt('asq_option_levenshtein_3'),
                self::TM_LEVENSHTEIN_4 => $DIC->language()->txt('asq_option_levenshtein_4'),
                self::TM_LEVENSHTEIN_5 => $DIC->language()->txt('asq_option_levenshtein_5')]);
        $fields[self::VAR_TEXT_MATCHING] = $text_matching;
        
        if ($config !== null) {
            $text_matching->setValue($config->getTextMatching());
        }
        
        return $fields;
    }
    
    public static function readConfig()
    {
        return TextSubsetScoringConfiguration::create(
            intval($_POST[self::VAR_TEXT_MATCHING]));
    }
}