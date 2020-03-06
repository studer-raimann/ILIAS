<?php

namespace ILIAS\AssessmentQuestion\Questions\Ordering;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Option\AnswerOptions;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\AbstractScoring;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\EmptyScoringDefinition;
use ilNumberInputGUI;

/**
 * Class OrderingScoring
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class OrderingScoring extends AbstractScoring
{

    const VAR_POINTS = 'os_points';


    function score(Answer $answer) : float
    {
        $reached_points = 0.0; 

        /** @var OrderingScoringConfiguration $scoring_conf */
        $scoring_conf = $this->question->getPlayConfiguration()->getScoringConfiguration();

        $answers = $answer->getSelectedOrder();

        $reached_points = $scoring_conf->getPoints();

        /* To be valid answers need to be in the same order as in the question definition
         * what means that the correct answer will just be an increasing amount of numbers
         * so if the number should get smaller it is an error.
         */
        for ($i = 0; $i < count($answers) - 1; $i++) {
            if ($answers[$i] > $answers[$i + 1]) {
                $reached_points = 0.0;
            }
        }
        
        return $reached_points;
    }

    protected function calculateMaxScore()
    {
        $this->max_score = $this->question->getPlayConfiguration()->getScoringConfiguration()->getPoints();
    }

    public function getBestAnswer() : Answer
    {
        $answers = [];

        for ($i = 1; $i <= count($this->question->getAnswerOptions()->getOptions()); $i++) {
            $answers[] = $i;
        }

        return OrderingAnswer::create($answers);
    }


    /**
     * @param AbstractConfiguration|null $config
     *
     * @return array|null
     */
    public static function generateFields(?AbstractConfiguration $config, AnswerOptions $options = null): ?array
    {
        /** @var OrderingScoringConfiguration $config */
        global $DIC;

        $fields = [];

        $points = new ilNumberInputGUI($DIC->language()->txt('asq_label_points'), self::VAR_POINTS);
        $points->setRequired(true);
        $points->setSize(2);
        $fields[self::VAR_POINTS] = $points;

        if ($config !== null) {
            $points->setValue($config->getPoints());
        }

        return $fields;
    }


    public static function readConfig()
    {
        return OrderingScoringConfiguration::create(
            floatval($_POST[self::VAR_POINTS]));
    }


    /**
     * @return string
     */
    public static function getScoringDefinitionClass() : string
    {
        return EmptyScoringDefinition::class;
    }


    public static function isComplete(Question $question) : bool
    {
        /** @var OrderingScoringConfiguration $config */
        $config = $question->getPlayConfiguration()->getScoringConfiguration();
        
        if (empty($config->getPoints())) {
            return false;
        }
        
        return true;
    }
}