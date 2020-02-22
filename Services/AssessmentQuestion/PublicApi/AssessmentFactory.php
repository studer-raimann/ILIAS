<?php
declare(strict_types=1);

namespace ILIAS\Services\AssessmentQuestion\PublicApi\Factory;

use ILIAS\Services\AssessmentQuestion\PublicApi\Authoring\AuthoringService;

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
     * This factory provides the following services
     * * Authoring Question Service
     * * Authoring Question List Service
     * * Authoring Question Import Service
     *
     * @param int $container_obj_id
     * @param int $actor_user_id
     *
     * @return AuthoringService
     */
    public function questionAuthoring(int $container_obj_id, int $actor_user_id) : AuthoringService
    {
        return new AuthoringService($container_obj_id, $actor_user_id);
    }

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