<?php
declare(strict_types=1);

namespace ILIAS\AssessmentQuestion\Questions\MultipleChoice;

use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Option\AnswerOption;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\AbstractScoring;

/**
 * Class MultipleChoiceScoring
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author Adrian Lüthi <al@studer-raimann.ch>
 * @author Björn Heyser <bh@bjoernheyser.de>
 * @author Martin Studer <ms@studer-raimann.ch>
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class MultipleChoiceScoring extends AbstractScoring
{

    function score(Answer $answer): float
    {
        $reached_points = 0;
        
        $selected_options = $answer->getSelectedIds();
        
        /** @var AnswerOption $answer_option */
        foreach ($this->question->getAnswerOptions()->getOptions() as $answer_option) {
            if (in_array($answer_option->getOptionId(), $selected_options)) {
                $reached_points += $answer_option->getScoringDefinition()->getPointsSelected();
            } else {
                $reached_points += $answer_option->getScoringDefinition()->getPointsUnselected();
            }
        }
        return $reached_points;
    }

    protected function calculateMaxScore()
    {
        $this->max_score = $this->score($this->getBestAnswer());
    }
    
    public function getBestAnswer(): Answer
    {
        $answers = [];

        /** @var AnswerOption $answer_option */
        foreach ($this->question->getAnswerOptions()->getOptions() as $answer_option) {
            /** @var MultipleChoiceScoringDefinition $score */
            $score = $answer_option->getScoringDefinition();
            if ($score->getPointsSelected() > $score->getPointsUnselected()) {
                $answers[] = $answer_option->getOptionId();
            }
        }

        rsort($answers);

        $length = $this->question->getPlayConfiguration()
            ->getEditorConfiguration()
            ->getMaxAnswers();
        $answers = array_slice($answers, 0, $length);

        return MultipleChoiceAnswer::create($answers);
    }

    public static function readConfig()
    {
        return MultipleChoiceScoringConfiguration::create();
    }
    
    public static function isComplete(Question $question): bool
    {    
        if (count($question->getAnswerOptions()->getOptions()) < 2) {
            return false;
        }

        foreach ($question->getAnswerOptions()->getOptions() as $option) {
            /** @var MultipleChoiceScoringDefinition $option_config */
            $option_config = $option->getScoringDefinition();

            if (empty($option_config->getPointsSelected()) &&
                empty($option_config->getPointsUnselected()))
            {
                return false;
            }
        }

        return true;
    }
}