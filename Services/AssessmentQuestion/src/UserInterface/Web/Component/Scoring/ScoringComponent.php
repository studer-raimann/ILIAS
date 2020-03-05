<?php

namespace ILIAS\AssessmentQuestion\UserInterface\Web\Component\Feedback;

use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\DomainModel\QuestionPlayConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\AbstractScoring;
use ilTemplate;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class ScoringComponent
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ScoringComponent
{

    /**
     * @var QuestionDto
     */
    private $question_dto;
    /**
     * @var AbstractValueObject
     */
    private $answer;
    /**
     * @var AbstractScoring
     */
    private $scoring;


    //QuestionDto $question_dto, QuestionConfig $question_config, QuestionCommands $question_commands
    public function __construct(QuestionDto $question_dto, AbstractValueObject $answer)
    {
        $this->question_dto = $question_dto;
        $this->answer = new Answer(0, '', '', 0, 0, $answer);

        $scoring_class = $question_dto->getPlayConfiguration()->getScoringConfiguration()->configurationFor();
        $this->scoring = new $scoring_class($question_dto);
    }


    public function getHtml() : string
    {
        global $DIC;
        $DIC->language()->loadLanguageModule('assessment');
        $tpl = new ilTemplate("tpl.answer_scoring.html", true, true, "Services/AssessmentQuestion");

        $score_dto = $this->scoring->score($this->answer);

        $tpl->setCurrentBlock('answer_scoring');
        $tpl->setVariable('ANSWER_SCORE', sprintf($DIC->language()->txt("you_received_a_of_b_points"), $score_dto->getReachedPoints(), $score_dto->getMaxPoints()));
        $tpl->parseCurrentBlock();

        return $tpl->get();
    }
}