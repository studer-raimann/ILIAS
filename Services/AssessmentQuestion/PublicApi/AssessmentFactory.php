<?php
declare(strict_types=1);

namespace ILIAS\Services\AssessmentQuestion\PublicApi\Factory;

/**
 * Class AssessmentServices
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 *
 * @package ILIAS\Services\AssessmentQuestion\PublicApi\Factory
 */
class AssessmentFactory
{
    /**
     * @var QuestionService
     */
    private $question_service;
    
    /**
     * @return QuestionService
     */
    public function question() {
        if (is_null($this->question_service)) {
            $this->question_service = new QuestionService();
        }
        
        return $this->question_service;
    }
    
    /**
     * @var AnswerService
     */
    private $answer_service;
    
    /**
     * @return AnswerService
     */
    public function answer() {
        if (is_null($this->answer_service)) {
            $this->answer_service = new AnswerService();
        }
        
        return $this->answer_service;
    }
}