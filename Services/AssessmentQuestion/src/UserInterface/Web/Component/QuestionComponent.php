<?php

namespace ILIAS\AssessmentQuestion\UserInterface\Web\Component;

use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\AbstractEditor;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Presenter\AbstractPresenter;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Presenter\DefaultPresenter;
use ilTemplate;

/**
 * Class QuestionComponent
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class QuestionComponent
{
    const DEFAULT_SUBMIT_CMD = "submitAnswer";
    const DEFAULT_SHOW_FEEDBACK_CMD = "showFeedback";
    const DEFAULT_GET_HINT_CMD = "getHint";
    
    /**
     * @var QuestionDto
     */
    private $question_dto;
    /**
     * @var AbstractPresenter
     */
    private $presenter;
    /**
     * @var AbstractEditor
     */
    private $editor;

    public function __construct(QuestionDto $question_dto)
    {
        $this->question_dto = $question_dto;

        $presenter_class = DefaultPresenter::class;
        $presenter = new $presenter_class($question_dto);

        $editor_class = $question_dto->getPlayConfiguration()->getEditorConfiguration()->configurationFor();
        $editor = new $editor_class($question_dto);

        $this->presenter = $presenter;
        $this->editor = $editor;
    }


    public function renderHtml() : string
    {
        global $DIC;

        $tpl = new ilTemplate("tpl.question_view.html", true, true, "Services/AssessmentQuestion");

        $tpl->setCurrentBlock('question');
        $tpl->setVariable('SCORE_COMMAND', self::DEFAULT_SUBMIT_CMD);
        $tpl->setVariable('QUESTION_OUTPUT', $this->presenter->generateHtml($this->editor));
        $tpl->setVariable('BUTTON_TITLE', $DIC->language()->txt('check'));
        $tpl->parseCurrentBlock();

        return $tpl->get();
    }


    public function readAnswer()
    {
        return $this->editor->readAnswer();
    }


    public function setAnswer(Answer $answer)
    {
        $this->editor->setAnswer($answer->getValue());
    }
}