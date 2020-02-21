<?php

namespace ILIAS\AssessmentQuestion\UserInterface\Web\Component\Feedback;

use ILIAS\AssessmentQuestion\DomainModel\AnswerScoreDto;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\DomainModel\QuestionPlayConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\AbstractScoring;
use ilTemplate;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class AnswerFeedbackComponent
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class AnswerFeedbackComponent
{
    /**
     * @var QuestionDto
     */
    private $question_dto;
    /**
     * @var Answer
     */
    private $answer;
    /**
     * @var AbstractScoring
     */
    private $scoring;


    public function __construct(QuestionDto $question_dto, AbstractValueObject $answer)
    {
        $this->question_dto = $question_dto;
        $this->answer = new Answer(0, '', '', 0, 0, $answer);;

        $scoring_class = $question_dto->getPlayConfiguration()->getScoringConfiguration()->configurationFor();
        $this->scoring = new $scoring_class($question_dto);


    }


    public function getHtml() : string
    {
        $tpl = new ilTemplate("tpl.answer_feedback.html", true, true, "Services/AssessmentQuestion");

        include_once("./Services/Style/Content/classes/class.ilObjStyleSheet.php");

        $answer_score_dto = $this->scoring->score($this->answer);

        $tpl->setCurrentBlock('answer_feedback');

        if($answer_score_dto->getAnswerFeedbackType() === AnswerScoreDto::ANSWER_FEEDBACK_TYPE_CORRECT) {
            $answer_feedback = $this->question_dto->getFeedback()->getAnswerCorrectFeedback();
            $answer_feedback_css_class = AnswerScoreDto::CSS_CLASS_FEEDBACK_TYPE_CORRECT;
        }

        if($answer_score_dto->getAnswerFeedbackType() === AnswerScoreDto::ANSWER_FEEDBACK_TYPE_INCORRECT) {
            $answer_feedback = $this->question_dto->getFeedback()->getAnswerWrongFeedback();
            $answer_feedback_css_class = AnswerScoreDto::CSS_CLASS_FEEDBACK_TYPE_WRONG;
        }


        $tpl->setVariable('ANSWER_FEEDBACK', $answer_feedback);
        $tpl->setVariable('ILC_FB_CSS_CLASS', $answer_feedback_css_class);


        $tpl->parseCurrentBlock();

        return $tpl->get();
    }
}