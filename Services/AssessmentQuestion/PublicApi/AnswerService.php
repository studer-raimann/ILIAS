<?php
declare(strict_types=1);

namespace ILIAS\Services\AssessmentQuestion\PublicApi\Factory;

use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use srag\CQRS\Aggregate\AbstractValueObject;

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
    public function getScore(QuestionDto $question, AbstractValueObject $answer) : int {
        
    }
    
    public function getMaxScore(QuestionDto $question) : int {
        
    }
    
    public function getBestAnswer(QuestionDto $question) : AbstractValueObject {
        
    }
}