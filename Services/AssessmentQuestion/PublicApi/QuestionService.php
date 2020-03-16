<?php
declare(strict_types=1);

namespace ILIAS\Services\AssessmentQuestion\PublicApi\Factory;

use ILIAS\AssessmentQuestion\DomainModel\ContentEditingMode;
use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\DomainModel\QuestionRepository;
use ILIAS\AssessmentQuestion\DomainModel\Command\CreateQuestionCommand;
use ILIAS\AssessmentQuestion\DomainModel\Command\SaveQuestionCommand;
use ILIAS\AssessmentQuestion\UserInterface\Web\AsqGUIElementFactory;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\QuestionComponent;
use ILIAS\AssessmentQuestion\UserInterface\Web\Form\QuestionFormGUI;
use ilAsqException;
use ilAsqQuestionPageGUI;
use srag\CQRS\Aggregate\AbstractValueObject;
use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Command\CommandBusBuilder;
use ILIAS\AssessmentQuestion\Infrastructure\Persistence\EventStore\QuestionEventStoreRepository;

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
        return QuestionDto::CreateFromQuestion(QuestionRepository::getInstance()->getAggregateByIliasId($id));
    }
    
    public function getQuestionsOfContainer(int $container_id) : array {
        global $DIC;
        
        $questions = [];
        $event_store = new QuestionEventStoreRepository();
        foreach ($event_store->allStoredQuestionIdsForContainerObjId($container_id) as $aggregate_id) {        
            $questions[] = $DIC->assessment()->question()->getQuestionByQuestionId($aggregate_id);;
        }
        
        return $questions;
    }
    
    public function getQuestionComponent(QuestionDto $question) : QuestionComponent {
        global $DIC;
        
        $DIC->language()->loadLanguageModule('asq');
        
        return new QuestionComponent($question);
    }
     
    public function getQuestionEditForm(QuestionDto $question) : QuestionFormGUI {
        return AsqGUIElementFactory::CreateQuestionForm($question);
    }
    
    public function getQuestionPage(QuestionDto $question_dto) : ilAsqQuestionPageGUI {
        $page_gui = new ilAsqQuestionPageGUI($question_dto->getContainerObjId(), $question_dto->getQuestionIntId(), $this->lng_key);
        $page_gui->setRenderPageContainer(false);
        $page_gui->setEditPreview(true);
        $page_gui->setEnabledTabs(false);
        $page_gui->setPresentationTitle($question_dto->getData()->getTitle());
        
        $question_component = $this->getQuestionComponent($question_dto);
        $page_gui->setQuestionComponent($question_component);
        
        return $page_gui;
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

    public function saveQuestion(QuestionDto $question_dto)
    {
        // check changes and trigger them on question if there are any
        /** @var Question $question */
        $question = QuestionRepository::getInstance()->getAggregateRootById(new DomainObjectId($question_dto->getId()));
        
        if (! AbstractValueObject::isNullableEqual($question_dto->getData(), $question->getData())) {
            $question->setData($question_dto->getData(), $this->getActiveUser());
        }

        if (! AbstractValueObject::isNullableEqual($question_dto->getPlayConfiguration(), $question->getPlayConfiguration())) {
            $question->setPlayConfiguration($question_dto->getPlayConfiguration(), $this->getActiveUser());
        }

        if (! $question_dto->getAnswerOptions()->equals($question->getAnswerOptions())) {
            $question->setAnswerOptions($question_dto->getAnswerOptions(), $this->getActiveUser());
        }

        if (is_object($question_dto->getFeedback()) && !AbstractValueObject::isNullableEqual($question_dto->getFeedback(), $question->getFeedback())) {
            $question->setFeedback($question_dto->getFeedback(), $this->getActiveUser());
        }

        if (! is_null($question_dto->getQuestionHints()) && !$question_dto->getQuestionHints()->equals($question->getHints())) {
            $question->setHints($question_dto->getQuestionHints(), $this->getActiveUser());
        }

        if (count($question->getRecordedEvents()->getEvents()) > 0) {
            // save changes if there are any
            CommandBusBuilder::getCommandBus()->handle(new SaveQuestionCommand($question, $this->getActiveUser()));
        }
    }
    
    public function deleteQuestion(QuestionDto $question) {
        
    }
}