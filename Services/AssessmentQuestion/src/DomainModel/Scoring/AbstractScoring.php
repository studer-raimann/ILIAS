<?php

namespace ILIAS\AssessmentQuestion\DomainModel\Scoring;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Option\AnswerOptions;

/**
 * Abstract Class AbstractScoring
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
abstract class AbstractScoring
{
    const ANSWER_CORRECT = 1;
    const ANSWER_INCORRECT = 2;
    const ANSWER_CORRECTNESS_NOT_DETERMINABLLE = 3;
    
    const SCORING_DEFINITION_SUFFIX = 'Definition';
    
    /**
     * @var QuestionDto
     */
    protected $question;

    /**
     * @var float
     */
    protected $max_score;

    /**
     * AbstractScoring constructor.
     *
     * @param QuestionDto $question
     * @param array       $configuration
     */
    public function __construct(QuestionDto $question)
    {
        $this->question = $question;
    }

    /**
     * @return array|null
     */
    public static function generateFields(?AbstractConfiguration $config, AnswerOptions $options = null) : ?array
    {
        return [];
    }


    public static abstract function readConfig();


    /**
     * @return string
     */
    public static function getScoringDefinitionClass() : string
    {
        return get_called_class() . self::SCORING_DEFINITION_SUFFIX;
    }


    public static abstract function isComplete(Question $question) : bool;

    /**
     * @param Answer $answer
     * @return float
     */
    abstract function score(Answer $answer) : float;

    public function getMaxScore() : float {
        if (is_null($this->max_score)) {
            $this->calculateMaxScore();
        }
        
        return $this->max_score;
    }
    
    protected abstract function calculateMaxScore();
    
    /**
     * @param float $reached_points
     * @param float $max_points
     *
     * @return int
     */
    public function getAnswerFeedbackType(float $reached_points) : int
    {
        if ($this->getMaxScore() < PHP_FLOAT_EPSILON) {
            return self::ANSWER_CORRECTNESS_NOT_DETERMINABLLE;
        }
        else if (abs($reached_points - $this->getMaxScore()) < PHP_FLOAT_EPSILON) {
            return self::ANSWER_CORRECT;
        }
        else {
            return self::ANSWER_INCORRECT;
        }
    }
    
    public abstract function getBestAnswer() : Answer;
}