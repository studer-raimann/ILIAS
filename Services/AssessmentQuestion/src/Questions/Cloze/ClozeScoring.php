<?php

namespace ILIAS\AssessmentQuestion\Questions\Cloze;

use ILIAS\AssessmentQuestion\DomainModel\AnswerScoreDto;
use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\AbstractScoring;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\EmptyScoringDefinition;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\TextScoring;

/**
 * Class ClozeScoring
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ClozeScoring extends AbstractScoring {
    /**
     * @var ClozeEditorConfiguration
     */
    protected $configuration;
    
    /**
     * @param QuestionDto $question
     */
    public function __construct($question) {
        parent::__construct($question);
        
        $this->configuration = $question->getPlayConfiguration()->getEditorConfiguration();
    }
    
    private $reached_points;
    private $max_points;
    
    public function score(Answer $answer): AnswerScoreDto
    {
        $given_answer = $answer->getValue()->getAnswers();
        
        $this->reached_points = 0;
        $this->max_points = 0;
        
        for ($i = 1; $i <= count($this->configuration->getGaps()); $i += 1) {
            
            $gap_configuration = $this->configuration->getGaps()[$i - 1];
            
            if (get_class($gap_configuration) === SelectGapConfiguration::class) {
                $this->scoreSelectGap($given_answer[$i], $gap_configuration);
            }
            else if (get_class($gap_configuration) === TextGapConfiguration::class) {
                $this->scoreTextGap($given_answer[$i], $gap_configuration);
            }
            else if (get_class($gap_configuration) === NumericGapConfiguration::class) {
                $this->scoreNumericGap(floatval($given_answer[$i]), $gap_configuration);
            }
        }
        
        return $this->createScoreDto(
            $answer, 
            $this->max_points, 
            $this->reached_points, 
            $this->getAnswerFeedbackType($this->reached_points,$this->max_points));
    }
    
    /**
     * @param string $answer
     * @param SelectGapConfiguration $gap_configuration
     */
    private function scoreSelectGap(string $answer, SelectGapConfiguration $gap_configuration)
    {
        $gap_max = 0;
        
        /** @var $gap ClozeGapItem */
        foreach($gap_configuration->getItems() as $gap_item) {
            if ($gap_item->getPoints() > $gap_max) {
                $gap_max = $gap_item->getPoints();
            }
            
            if ($answer === $gap_item->getText()) {
                $this->reached_points += $gap_item->getPoints();
            }
        }
        
        $this->max_points += $gap_max;
    }

    /**
     * @param string $answer
     * @param TextGapConfiguration $gap_configuration
     */
    private function scoreTextGap(string $answer, TextGapConfiguration $gap_configuration)
    {
        $gap_max = 0;
        
        /** @var $gap ClozeGapItem */
        foreach($gap_configuration->getItems() as $gap_item) {
            if ($gap_item->getPoints() > $gap_max) {
                $gap_max = $gap_item->getPoints();
            }
            
            if (TextScoring::isMatch($answer, $gap_item->getText(), $gap_configuration->getMatchingMethod())) {
                $this->reached_points += $gap_item->getPoints();
            }
        }
        
        $this->max_points += $gap_max;
    }
    
    /**
     * @param float $answer
     * @param NumericGapConfiguration $gap_configuration
     */
    private function scoreNumericGap(float $answer, NumericGapConfiguration $gap_configuration) {
        if ($gap_configuration->getUpper() >= $answer &&
            $gap_configuration->getLower() <= $answer) {
            $this->reached_points += $gap_configuration->getPoints();
        }
        
        $this->max_points += $gap_configuration->getPoints();
    }

    public static function readConfig()
    {
        return ClozeScoringConfiguration::create();
    }

    public static function isComplete(Question $question): bool
    {
        return true;
    }
    
    /**
     * @return string
     */
    public static function getScoringDefinitionClass() : string
    {
        return EmptyScoringDefinition::class;
    }
    
}