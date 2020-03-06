<?php
declare(strict_types=1);

namespace ILIAS\Services\AssessmentQuestion\PublicApi\Factory;

use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\AbstractScoring;

/**
 * Class AnswerService
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 *
 * @package ILIAS\Services\AssessmentQuestion\PublicApi\Factory
 */
class AnswerService extends ASQService {
    public function getScore(QuestionDto $question, Answer $answer) : float {
        $scoring_class = $question->getPlayConfiguration()->getScoringConfiguration()->configurationFor();
        /** @var $scoring AbstractScoring */
        $scoring = new $scoring_class($question);
        return $scoring->score($answer);
    }
    
    public function getMaxScore(QuestionDto $question) : float {
        $scoring_class = $question->getPlayConfiguration()->getScoringConfiguration()->configurationFor();
        /** @var $scoring AbstractScoring */
        $scoring = new $scoring_class($question);
        return $scoring->getMaxScore();
    }
    
    public function getBestAnswer(QuestionDto $question) : Answer {
        $scoring_class = $question->getPlayConfiguration()->getScoringConfiguration()->configurationFor();
        /** @var $scoring AbstractScoring */
        $scoring = new $scoring_class($question);
        return $scoring->getBestAnswer();
    }
}