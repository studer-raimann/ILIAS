<?php
declare(strict_types=1);

namespace ILIAS\Services\AssessmentQuestion\PublicApi\Factory;

use ILIAS\AssessmentQuestion\DomainModel\ContentEditingMode;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\DomainModel\QuestionRepository;
use ILIAS\AssessmentQuestion\DomainModel\Command\CreateQuestionCommand;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\QuestionComponent;
use ILIAS\AssessmentQuestion\UserInterface\Web\Form\QuestionFormGUI;
use srag\CQRS\Command\CommandBusBuilder;
use srag\CQRS\Aggregate\DomainObjectId;
use ilAsqException;

/**
 * Class QuestionService
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 *
 * @package ILIAS\Services\AssessmentQuestion\PublicApi\Factory
 */
class QuestionService extends ASQService
{
    public function getQuestionByQuestionId(string $id) : QuestionDto {
        $question = QuestionRepository::getInstance()->getAggregateRootById(new DomainObjectId($id));
        
        if(is_object($question->getAggregateId())) {
            return QuestionDto::CreateFromQuestion($question);
        }
        else {
            //TODO translate?
            throw new ilAsqException(sprintf("Question with id %s does not exist", $id));
        }
    }
    
    public function getQuestionByIliasObjectId(int $id) : QuestionDto {
        
    }
    
    public function getQuestionComponent(QuestionDto $question) : QuestionComponent {
        
    }
    
    public function getQuestionEditForm(QuestionDto $question) : QuestionFormGUI {
        
    }

    public function createQuestion(int $type, int $container_id, string $content_editing_mode = ContentEditingMode::RTE_TEXTAREA): QuestionDto
    {
        $id = new DomainObjectId();
        
        // CreateQuestion.png
        CommandBusBuilder::getCommandBus()->handle(
            new CreateQuestionCommand(
                $id,
                $type, 
                $this->getActiveUser(), 
                $container_id, 
                $content_editing_mode));
        
        return $this->getQuestionByQuestionId($id->getId());
    }
    
    public function saveQuestion(QuestionDto $question) {
        
    }
    
    public function deleteQuestion(QuestionDto $question) {
        
    }
}