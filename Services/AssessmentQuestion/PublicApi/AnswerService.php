<?php
declare(strict_types=1);

namespace ILIAS\Services\AssessmentQuestion\PublicApi\Factory;

use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\AbstractScoring;
use ILIAS\AssessmentQuestion\Infrastructure\Persistence\SimpleStoredAnswer;
use ilAsqException;

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
    /**
     * @param QuestionDto $question
     * @param Answer $answer
     * @return float
     */
    public function getScore(QuestionDto $question, Answer $answer) : float {
        $scoring_class = $question->getPlayConfiguration()->getScoringConfiguration()->configurationFor();
        /** @var $scoring AbstractScoring */
        $scoring = new $scoring_class($question);
        return $scoring->score($answer);
    }
    
    /**
     * @param QuestionDto $question
     * @return float
     */
    public function getMaxScore(QuestionDto $question) : float {
        $scoring_class = $question->getPlayConfiguration()->getScoringConfiguration()->configurationFor();
        /** @var $scoring AbstractScoring */
        $scoring = new $scoring_class($question);
        return $scoring->getMaxScore();
    }
    
    /**
     * @param QuestionDto $question
     * @return Answer
     */
    public function getBestAnswer(QuestionDto $question) : Answer {
        $scoring_class = $question->getPlayConfiguration()->getScoringConfiguration()->configurationFor();
        /** @var $scoring AbstractScoring */
        $scoring = new $scoring_class($question);
        return $scoring->getBestAnswer();
    }
    
    /**
     * @param Answer $answer
     * @param string $uuid
     * @return string
     */
    public function storeAnswer(Answer $answer, ?string $uuid = null) : string {
        $stored = SimpleStoredAnswer::createNew($answer, $uuid);
        $stored->create();
        return $stored->getUuid();
    }
    
    /**
     * @param string $uuid
     * @param int $version
     * @return Answer
     */
    public function getAnswer(string $uuid, ?int $version = null) : Answer {
        if (is_null($version)) {
            $stored = SimpleStoredAnswer::where(['uuid' => $uuid])->orderBy('version', 'DESC')->first();
        }
        else {
            $stored = SimpleStoredAnswer::where(['uuid' => $uuid, 'version' => $version])->first();
        }
        
        if (is_null($stored)) {
            throw new ilAsqException(sprintf('The requested Answer does not exist UUID = "%s" Version = "%s"', $uuid, $version));
        }
        
        return $stored->getAnswer();
    }
    
    public function getAnswerHistory(string $uuid) : array {
        $history = SimpleStoredAnswer::where(['uuid' => $uuid])->get();
        
        $answers = [];
        
        foreach($history as $stored_answer) {
            $answers[$stored_answer->getVersion()] = $stored_answer->getAnswer();
        }
        
        return $answers;
    }
}