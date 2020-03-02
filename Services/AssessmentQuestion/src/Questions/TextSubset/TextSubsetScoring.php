<?php

namespace ILIAS\AssessmentQuestion\Questions\TextSubset;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\AnswerScoreDto;
use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Option\AnswerOptions;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\AbstractScoring;
use Exception;
use ilSelectInputGUI;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\TextScoring;

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

    /**
     * @var Answer
     */
    protected $answer;
    
    const VAR_TEXT_MATCHING = 'tss_text_matching';
    
    /**
     * {@inheritDoc}
     * @see \ILIAS\AssessmentQuestion\DomainModel\Scoring\AbstractScoring::score()
     */
    public function score(Answer $answer) : AnswerScoreDto
    {
        $this->answer = $answer;

        /** @var TextSubsetScoringConfiguration $scoring_conf */
        $scoring_conf = $this->question->getPlayConfiguration()->getScoringConfiguration();
        
        switch ($scoring_conf->getTextMatching()) {
            case TextScoring::TM_CASE_INSENSITIVE:
                return $this->caseInsensitiveScoring();
            case TextScoring::TM_CASE_SENSITIVE:
                return $this->caseSensitiveScoring();
            case TextScoring::TM_LEVENSHTEIN_1:
                return $this->levenshteinScoring(1);
            case TextScoring::TM_LEVENSHTEIN_2:
                return $this->levenshteinScoring(2);
            case TextScoring::TM_LEVENSHTEIN_3:
                return $this->levenshteinScoring(3);
            case TextScoring::TM_LEVENSHTEIN_4:
                return $this->levenshteinScoring(4);
            case TextScoring::TM_LEVENSHTEIN_5:
                return $this->levenshteinScoring(5);
        }
        
        throw new Exception("Unknown Test Subset Scoring Method found");
    }
    
    public function getBestAnswer(): Answer
    {
        $answers = [];
        
        foreach ($this->question->getAnswerOptions()->getOptions() as $option) {
            $answers[] = $option->getScoringDefinition()->getText();
        }
        
        return new Answer(0, $this->question->getId(), '',0,0, json_encode($answers));
    }


    
    /**
     * @param array $answer_arr
     * @return int
     */
    private function caseInsensitiveScoring() : AnswerScoreDto {
        $reached_points = 0;
        $max_points = self::calculateMaxPoints($this->question);
        
        foreach ($this->answer->getValue()->getAnswers() as $result) {
            foreach ($this->question->getAnswerOptions()->getOptions() as $correct) {
                if (strtoupper($correct->getScoringDefinition()->getText()) === strtoupper($result)) {
                    $reached_points += $correct->getScoringDefinition()->getPoints();
                    break;
                }
            }
        }

        return $this->createScoreDto($this->answer, $max_points, $reached_points, $this->getAnswerFeedbackType($reached_points,$max_points));
    }
    
    /**
     * @param array $answer_arr
     * @return int
     */
    private function caseSensitiveScoring() : AnswerScoreDto {
        $reached_points = 0;
        $max_points = self::calculateMaxPoints($this->question);
 
        foreach ($this->answer->getValue()->getAnswers() as $result) {
            foreach ($this->question->getAnswerOptions()->getOptions() as $correct) {
                if ($correct->getScoringDefinition()->getText() === $result) {
                    $reached_points += $correct->getScoringDefinition()->getPoints();
                    break;
                }
            }
        }

        return $this->createScoreDto($this->answer, $max_points, $reached_points, $this->getAnswerFeedbackType($reached_points,$max_points));
    }
    
    /**
     * @param array $answer_arr
     * @param int $distance
     * @return int
     */
    private function levenshteinScoring(int $distance) : AnswerScoreDto {
        $reached_points = 0;
        $max_points = $max_points = self::calculateMaxPoints($this->question);
        
        foreach ($this->answer->getValue()->getAnswers() as $result) {
            foreach ($this->question->getAnswerOptions()->getOptions() as $correct) {
                if (levenshtein($correct->getScoringDefinition()->getText(), $result) <= $distance) {
                    $reached_points += $correct->getScoringDefinition()->getPoints();
                    break;
                }
            }
        }

        return $this->createScoreDto($this->answer, $max_points, $reached_points, $this->getAnswerFeedbackType($reached_points,$max_points));
    }
    
    /**
     * @param AbstractConfiguration|null $config
     *
     * @return array|null
     */
    public static function generateFields(?AbstractConfiguration $config, AnswerOptions $options = null): ?array {
        /** @var TextSubsetScoringConfiguration $config */
        global $DIC;
        
        $fields = [];

        $text_matching = TextScoring::getScoringTypeSelectionField(self::VAR_TEXT_MATCHING);
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
    
    public static function calculateMaxPoints(QuestionDto $question) :int {
        $amount = $question->getPlayConfiguration()->getEditorConfiguration()->getNumberOfRequestedAnswers();
        
        if(empty($amount)) {
            return 0;
        }
        
        $points = array_map(function($option) {
            return $option->getScoringDefinition()->getPoints();
        },
        $question->getAnswerOptions()->getOptions());
            
            rsort($points);
            
        return array_reduce(array_slice($points, 0, $amount),
            function($a, $b) {
                return $a + $b;
            });
    }
    
    public static function isComplete(Question $question): bool
    {
        /** @var TextSubsetScoringConfiguration $config */
        $config = $question->getPlayConfiguration()->getScoringConfiguration();
        
        if (empty($config->getTextMatching())) {
            return false;
        }
        
        foreach ($question->getAnswerOptions()->getOptions() as $option) {
            /** @var TextSubsetScoringDefinition $option_config */
            $option_config = $option->getScoringDefinition();
            
            if (empty($option_config->getText()) ||
                empty($option_config->getPoints()))
            {
                return false;
            }
        }
        
        return true;
    }
}