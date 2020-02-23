<?php

namespace ILIAS\AssessmentQuestion\UserInterface\Web\Component\Feedback;

use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ilTemplate;

/**
 * Class FeedbackComponent
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class FeedbackComponent
{
    const FEEDBACK_FOCUS_ANCHOR = 'focus';

    /**
     * @var ScoringComponent
     */
    private $scoring_component;
    /**
     * @var AnswerFeedbackComponent
     */
    private $answer_feedback_component;


//QuestionDto $question_dto, QuestionConfig $question_config, QuestionCommands $question_commands
    public function __construct(ScoringComponent $scoring_component, AnswerFeedbackComponent $answer_feedback_component)
    {
        $this->scoring_component = $scoring_component;
        $this->answer_feedback_component = $answer_feedback_component;
    }


    public function getHtml() : string
    {
        global $DIC;

        $tpl = new ilTemplate("tpl.feedback.html", true, true, "Services/AssessmentQuestion");

        /*$tpl->setCurrentBlock('inst_resp_id');
        $tpl->setVariable('INSTANT_RESPONSE_FOCUS_ID', self::FEEDBACK_FOCUS_ANCHOR);
        $tpl->parseCurrentBlock();*/

        $tpl->setCurrentBlock('feedback_header');
        $tpl->setVariable('FEEDBACK_HEADER', $DIC->language()->txt('asq_answer_feedback_header'));
        $tpl->parseCurrentBlock();

        $tpl->setCurrentBlock('answer_feedback');
        $tpl->setVariable('ANSWER_FEEDBACK', $this->answer_feedback_component->getHtml());
        $tpl->parseCurrentBlock();


        $tpl->setCurrentBlock('answer_scoring');
        $tpl->setVariable('ANSWER_SCORING', $this->scoring_component->getHtml());
        $tpl->parseCurrentBlock();

        return $tpl->get();
    }


    public function readAnswer() : string
    {
        return $this->editor->readAnswer();
    }


    public function setAnswer(Answer $answer)
    {
        $this->editor->setAnswer($answer->getValue());
    }
}